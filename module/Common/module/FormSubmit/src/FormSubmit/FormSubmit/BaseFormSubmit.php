<?php

namespace FormSubmit\FormSubmit;

use Zend\EventManager\EventManager;

Class BaseFormSubmit
{
	protected $serviceLocator;//serviceManage
	protected $type;//表单提交处理类型(插入或更新)
	protected $dbModel;//数据库操作模块
	protected $dbInsertFunction;//插入函数名
	protected $insertExistsFunction;//插入数据存在检查函数名
	protected $insertExistsSelect;//自定义插入数据存在检查(Db\Sql\Select对象)
	protected $lastInsertId;//主表插入id
	protected $dbUpdateFunction;////更新函数名
	protected $updateExistsFunction;//更新数据存在函数名
	protected $updateExistsSelect;//自定义更新数据存在检查(Db\Sql\Select对象)
	protected $updateExistsValue;//检查更新数据是否存在时的对比值
	protected $validateModel;//验证模块
	protected $validateFunction;//验证函数名
	protected $quickValidateFunction;//快速验证函数名
	protected $validatedData;//验证后数据
	protected $uploadPath;//上传图片路径
	protected $initArray;//配置信息
	protected $isVal;//是否通过验证
	protected $isExists;//数据是否存在
	protected $isUpdateWhere;//数据库更新是否有条件
	protected $isTransaction;//是否开启事务
	protected $isRollBack;//开启回滚
	protected $isCustomExists;//是否自定义数据验证
	protected $validateErrorMessage;//验证错误信息
	protected $data;//传入原始数据
	protected $tableName;//操作表名
	protected $chlidColumns = array();//子表字段集
	protected $chlidColumnsValues;//子表字段集值
	
	function __construct($initArray,$serviceLocator)
	{
		$this->initArray = $initArray;
		$this->serviceLocator = $serviceLocator;
		$this->isTransaction = false;
		$this->isUpdateWhere = true;
		$this->isCustomExists = false;
		$this->tableName = false;
		$this->events = new EventManager();
	}
	
	/**
	 * 回滚上传图片
	 * @param string $type
	 */
	private function rollBackUpload($type)
	{
		$uploadPath = $this->uploadPath;
		if($type == 'insert' && is_array($uploadPath) && count($uploadPath) > 0) {
			foreach($uploadPath as $file) {
				is_file($file) && @unlink($file);
			}
		}
	}
	
	/**
	 * 设置表单操作信息
	 * @param string $dbDispatchName 数据库操作模块分发名
	 * @param string $validateDispatName 验证模块分发名
	 *
	 * $validateDispatName为false时，验证模块分发名使用数据库操作模块分发名值
	 */
	protected function setInfo($type,$initArray,$dbDispatchName,$validateDispatName)
	{
		$this->type = $type;
		//如果数据库操作模块分发名不是一个数组，执行模块分发
		!is_array($dbDispatchName) && $this->dbModel = $this->serviceLocator->get($initArray['dbModelName'])->$dbDispatchName();
		//$validateDispatName为false时，验证模块分发名使用数据库操作模块分发名值
		$validateDispatName === false ? $this->validateModel = $this->serviceLocator->get($initArray['validateModelName'])->$dbDispatchName() : $this->validateModel = $this->serviceLocator->get($initArray['validateModelName'])->$validateDispatName();
		$this->dbInsertFunction = $initArray['dbInsertFunction'];
		$this->insertExistsFunction = $initArray['insertExistsFunction'];
		$this->dbUpdateFunction = $initArray['dbUpdateFunction'];
		$this->updateExistsFunction = $initArray['updateExistsFunction'];
		$this->validateFunction = $initArray['validateFunction'];
		$this->quickValidateFunction = $initArray['quickValidateFunction'];
	}
	
	/**
	 * 验证数据存在数据查询参数
	 * @param array $data
	 * @param array $existsParams
	 * @return array
	 */
	protected function getExistsArray($data,$existsParams)
	{
		$existsArray = array();
		foreach($existsParams as $param) {
			if(!array_key_exists($param,$data)) {
				throw new \FormSubmit\Exception\FormSubmitException('exists param not in validate');
				return false;
			}
			$existsArray[$param] = $data[$param];
		}
	
		return $existsArray;
	}
	
	/**
	 * 自定义检查数据是否存在
	 * @param array $existsArray
	 * @return boolean
 	 */
	protected function customExists($existsArray)
	{
		$type = $this->type;
		$exists = false;
		if($type == 'insert') {
			$insertExistsFunction = $this->insertExistsFunction;
			if(method_exists($this->dbModel,$insertExistsFunction) === false) throw new \FormSubmit\Exception\FormSubmitException($insertExistsFunction.' method is undefined');
			$exists = $this->dbModel->$insertExistsFunction($existsArray);
		}
		else {
			$updateExistsFunction = $this->updateExistsFunction;
			if(method_exists($this->dbModel,$updateExistsFunction) === false) throw new \FormSubmit\Exception\FormSubmitException($updateExistsFunction.' method is undefined');
			$mainParams = $this->dbModel->$updateExistsFunction($existsArray);
			$mainParams !== false && $mainParams != current($this->updateExistsValue) && $exists = true;
		}

		return $exists;
	}
	
	/**
	 * 检查数据是否存在
	 * @param string $table 数据表名
	 * @param array $existsArray
	 * @return boolean
	 */
	protected function exists($table,$existsArray)
	{
		$type = $this->type;
		$adapter = $this->serviceLocator->get('adapter');
		$exists = false;

		foreach($existsArray as $existsDataKey => $existsDataValue) {
			if($type == 'insert') {
				$insertExistsSelect = $this->insertExistsSelect;
				is_object($insertExistsSelect) ? $recordExistsOption = $insertExistsSelect : $recordExistsOption = array('table' => $table,'field' => $existsDataKey,'adapter' => $adapter);
			}
			else {
				$updateExistsSelect = $this->updateExistsSelect;
				is_object($updateExistsSelect) ? $recordExistsOption = $updateExistsSelect : $recordExistsOption = array('table' => $table,'field' => $existsDataKey,'adapter' => $adapter,'exclude' => array('field' => key($this->updateExistsValue),'value' => current($this->updateExistsValue)));
			}
			$validator = new \Zend\Validator\Db\RecordExists($recordExistsOption);
			
			if($validator->isValid($existsDataValue) === false) return false;
		}
			
		return true;
	}
	
	/**
	 * 检查传入原始数据正确性
	 * @param array $initArray
	 * @param array $params
	 * @param array|string $dbDispatchName
	 * @param string $validateDispatName
	 * @return boolean
	 */
	public function paramsIsOk($initArray,$params,$dbDispatchName,$validateDispatName)
	{
	    if(!isset($initArray) || !is_array($params) || count($params) <= 0) return false;
	    $return = true;
	    if(is_array($dbDispatchName)) {
	        //如果数据库操作模块是数组形式，则准备使用本程序提供数据库操作方法，此处为操作表名赋值
	    	isset($dbDispatchName['table']) || $return = false;
	    	$return !== false && $this->tableName = $dbDispatchName['table'];
	    }
	    else {
	    	trim((string)$dbDispatchName) == '' && $return = false;
	    }

	    return $return;
	}
	
	/**
	 * 表单提交模块主函数
	 * @param string $type
	 * @param array $params
	 * @param array $existsParams
	 * @param string $dbDispatchName
	 * @param string $validateDispatName
	 * @return boolean
	 */
	protected function formSubmit($type,$params,$existsParams,$dbDispatchName,$validateDispatName)
	{
		$dbReturn = true;
		$initArray = $this->initArray;
		trim((string)$type) == '' && $type == 'insert';
		$isOk = $this->paramsIsOk($initArray,$params,$dbDispatchName,$validateDispatName);
		if($isOk === false) {
		    throw new \FormSubmit\Exception\FormSubmitException('source params is error');
		    return false;
		}
		$this->data = $params;

		$this->setInfo($type,$initArray,$dbDispatchName,$validateDispatName);

		//触发验证前事件
		$this->events->trigger('FormSubmit/ValidateBefore',$this,array());
		//验证输入参数
		$validateFunction = $this->validateFunction;
		if(!method_exists($this->validateModel,$validateFunction)) {
			throw new \FormSubmit\Exception\FormSubmitException('validate function is undefined');
			return false;
		}
		
		//快速验证或普通验证
		if($this->validateModel instanceof \Validate\Validate\QuickValidate) {
			$quickValidateFunction = $this->quickValidateFunction;
			if(!method_exists($this->validateModel,$quickValidateFunction)) {
				throw new \FormSubmit\Exception\FormSubmitException('quickvalidate function is undefined');
				return false;
			}
			$validation = $this->validateModel->$quickValidateFunction;
		}
		else {
			$validation = $this->validateModel->$validateFunction($this->data);
		}
		
		$this->isVal = true;
		if($validation === false) {
			$this->isVal = false;
			$this->validateErrorMessage = $this->validateModel->ErrorMessage();
			return false;
		}
		$this->validatedData = $this->validateModel->data;
		$this->uploadPath = $this->validateModel->uploadPath;
		if(!is_array($this->validatedData) || count($this->validatedData) <= 0) {
			throw new \FormSubmit\Exception\FormSubmitException('validate return data is empty');
			
			return false;
		}
		//触发验证后事件
		$this->events->trigger('FormSubmit/ValidateAfter',$this,array());

		//数据是否存在
		$exists = false;
		$this->isExists = false;
		if($existsParams !== false) {
			if(!is_array($existsParams) || count($existsParams) <= 0) {
				throw new \FormSubmit\Exception\FormSubmitException('exists params is not array or array is empty');
				return false;
			}
			//触发数据存在验证前事件
			$this->events->trigger('FormSubmit/ExistsBefore',$this,array());
			$existsArray = $this->getExistsArray($this->validatedData,$existsParams);
			if($existsArray === false) return false;
			//判断是否执行用户自定义数据存在验证方法
			if($this->isCustomExists === false) {
			    //如果没有提供数据操作表名，通过调用DbSql基础类“getTableName”函数取得表名
			    $this->tableName === false ? $tableName = $this->dbModel->getTableName() : $tableName = $this->tableName;
			    $exists = $this->exists($tableName,$existsArray);
			}
			else {
			    $exists = $this->customExists($existsArray);
			}
// 			$this->isCustomExists === false ? $exists = $this->exists($this->dbModel->getTableName(),$existsArray) : $exists = $this->customExists($existsArray);
			$this->isExists = $exists;
			//触发数据存在验证后事件
			$this->events->trigger('FormSubmit/ExistsAfter',$this,array());
		}

		//数据库操作
		if($exists === false) {
			//触发数据库操作前事件
			$this->events->trigger('FormSubmit/DbBefore',$this,array());
			$validatedData = $this->validatedData;
			if(is_array($this->chlidColumns) && count($this->chlidColumns) > 0) {
				$chlidColumnsValues = array();
				foreach($this->chlidColumns as $key => $columns) {
					foreach($columns as $column) {
						if(isset($validatedData[$column])) {
							$chlidColumnsValues[$key][$column] = $validatedData[$column];
							unset($validatedData[$column]);
						}
					}
				}
				$this->chlidColumnsValues = $chlidColumnsValues;
				$this->validatedData = $validatedData;
			}
			//是否开启事务
			$this->isTransaction === true && $this->dbModel->beginTransaction();
			//执行插入或更新
			if($type == 'insert') {
				$function = $this->dbInsertFunction;
				if($this->tableName !== false) {
				    //如果是程序自动插入，则调用函数执行插入
					$sql = new \FormSubmit\DbSql\DbSql($this->serviceLocator->get('adapter'),$this->tableName);
					$dbReturn = $sql->insert($validatedData);
					$dbReturn !== false && $this->lastInsertId = $dbReturn;
				}
				else {
				    if(!method_exists($this->dbModel,$function)) {
				    	throw new \FormSubmit\Exception\FormSubmitException('insert function is undefined');
				    	return false;
				    }
				    $dbReturn = $this->dbModel->$function($validatedData);
				    $this->lastInsertId = $this->dbModel->lastInsertId();
				}	
			}
			else {
				$function = $this->dbUpdateFunction;
				$this->isUpdateWhere === true ? $where = $this->updateExistsValue : $where = array();
				if($this->tableName !== false) {
				    //如果是程序自动更新，则调用函数执行更新
				    $sql = new \FormSubmit\DbSql\DbSql($this->serviceLocator->get('adapter'),$this->tableName);
				    $dbReturn = $sql->update($validatedData,$where);
				}
				else {
				    if(!method_exists($this->dbModel,$function)) {
				    	throw new \FormSubmit\Exception\FormSubmitException('update function is undefined');
				    	return false;
				    }
				    $dbReturn= $this->dbModel->$function($validatedData,$where);
				}
			}
			//触发数据库操作后事件
			$this->events->trigger('FormSubmit/DbAfter',$this,array());
			//$dbReturn === true ? $this->isTransaction === true && $this->dbModel->commit() : ($this->isTransaction === true || $this->isRollBack === true) && $this->dbModel->rollback() && $this->rollBackUpload($type);
			if($dbReturn === true) {
				$this->isTransaction === true && $this->dbModel->commit();
			}
			else if($this->isTransaction === true || $this->isRollBack === true) {
				$this->dbModel->rollback() && $this->rollBackUpload($type);
			}
			
			return $dbReturn;
		}
		
		return false;
	}
	
	/**
	 * 通过上传文件名创建子表字段集
	 * @param string $key 子表集键
	 * @param string $filesKey 上传文件键
	 * 
	 * 例：子表图片在表单内为"image[]"形式，则$filesKey为"image"，本函数为其创建子表集为
	 * 	   image0、image1 ......
	 */
	public function createChlidColumnsByFiles($key,$filesKey)
	{
		$request = new \Zend\Http\PhpEnvironment\Request;
		$chlidArray = array();
		$fileArray = $request->getFiles()->toArray();
		if(isset($fileArray[$filesKey])) {
			foreach($fileArray[$filesKey] as $fileKey => $file) {
				array_push($chlidArray,$filesKey.$fileKey);
			}
		}
		
		count($chlidArray) > 0 && $this->setChlidColumns($key,$chlidArray);
	}
	
	/**
	 * 通过表单名创建子表字段集
	 * @param string $key 子表集键
	 * @param string $inputName 表单项name
	 * 
	 * 例：子表在表单内为"ad[]"形式，则$inputName为"ad"，本函数为其创建子表集为
	 * 	   ad0、ad1 ......
	 */
	public function createChlidColumns($key,$inputName)
	{
		$sourceData = $this->getSourceData();
		$dataArray = array();
		if(isset($sourceData[$inputName])) {
			foreach($sourceData[$inputName] as $fileKey => $file) {
				array_push($dataArray,$inputName.$fileKey);
			}
		}

		count($dataArray) > 0 && $this->setChlidColumns($key,$dataArray);
	}
	
	/**
	 * 设置插入函数名
	 * @param string $dbInsertFunction
	 */
	public function setDbInsertFunction($dbInsertFunction)
	{
		$this->dbInsertFunction = $dbInsertFunction;
	}
	
	/**
	 * 设置插入数据存在检查函数名
	 * @param string $insertExistsFunction
	 */
	public function setInsertExistsFunction($insertExistsFunction)
	{
		$this->insertExistsFunction = $insertExistsFunction;
	}
	
	/**
	 * 设置更新函数名
	 * @param string $dbUpdateFunction
	 */
	public function setDbUpdateFunction($dbUpdateFunction)
	{
		$this->dbUpdateFunction = $dbUpdateFunction;
	}
	
	/**
	 * 设置数据库更新是否有条件
	 * @param boolean $isUpdateWhere
	 */
	public function setIsUpdateWhere($isUpdateWhere)
	{
		$this->isUpdateWhere = $isUpdateWhere;
	}
	
	/**
	 * 设置是否开启事务
	 * @param boolean $isTransaction
	 */
	public function setIsTransaction($isTransaction)
	{
		$this->isTransaction = $isTransaction;
	}
	
	/**
	 * 设置验证后数据
	 * @param array $data
	 */
	public function setValidatedData($data)
	{
		$this->validatedData = $data;
	}
	
	/**
	 * 设置原始数据
	 * @param array $data
	 */
	public function setSourceData($data)
	{
		$this->data = $data;
	}
	
	/**
	 * 设置子表字段集
	 * @param array $array
	 */
	public function setChlidColumns($key,$array)
	{
		$this->chlidColumns[$key] = $array;
		return $this;
	}
	
	/**
	 * 设置回滚
	 * @param boolean $isRollBack
	 */
	public function setIsRollBack($isRollBack)
	{
		$this->isRollBack = $isRollBack;
	}
	
	/**
	 * 设置自定义插入数据存在检查
	 * @param object $select
	 */
	public function setinsertExistsSelect($select)
	{
		$this->insertExistsSelect = $select;
	}
	
	/**
	 * 设置自定义更新数据存在检查
	 * @param object $select
	 */
	public function setupdateExistsSelect($select)
	{
		$this->updateExistsSelect = $select;
	}
	
	/**
	 * 设置是否自定义数据验证
	 * @param boolean $isCustomExists
	 */
	public function setIsCustomExists($isCustomExists)
	{
		$this->isCustomExists = $isCustomExists;
	}
	
	/**
	 * 是否通过验证
	 * @return boolean
	 */
	public function isVal()
	{
		return $this->isVal;
	}
	
	/**
	 * 数据是否存在
	 * @return boolean
	 */
	public function isExists()
	{
		return $this->isExists;
	}
	
	/**
	 * 获得serviceManager
	 */
	public function getServiceLocator()
	{
		return $this->serviceLocator;
	}
	
	/**
	 * 获得验证错误信息
	 * @return array
	 */
	public function getValidateErrorMessage()
	{
		return $this->validateErrorMessage;
	}
	
	/**
	 * 获得验证后数据
	 * @return array
	 */
	public function getValidatedData()
	{
		return $this->validatedData;
	}
	
	/**
	 * 获得原始数据
	 * @return array
	 */
	public function getSourceData()
	{
		return $this->data;
	}
	
	/**
	 * 获得最后插入id
	 */
	public function getLastInsertId()
	{
		return $this->lastInsertId;
	}
	
	public function getChlidColumnsValues($key)
	{
		return isset($this->chlidColumnsValues[$key]) ? $this->chlidColumnsValues[$key] : false;
	}
	
	/**
	 * 获得其它错误信息
	 * @return array
	 */
	public function getOtherErrorMessage()
	{
		return $this->otherErrorMessage;
	}
}