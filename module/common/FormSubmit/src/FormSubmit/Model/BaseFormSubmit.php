<?php

namespace FormSubmit\Model;

use Zend\EventManager\EventManager;

Class BaseFormSubmit
{
	protected $serviceLocator;//serviceManage
	protected $type;//表单提交处理类型(插入或更新)
	protected $dbModel;//数据库操作模块
	protected $dbInsertFunction;//插入函数名
	protected $insertExistsFunction;//插入数据存在检查函数名
	protected $lastInsertId;//主表插入id
	protected $dbUpdateFunction;////更新函数名
	protected $updateExistsFunction;//更新数据存在函数名
	protected $updateExistsValue;//检查更新数据是否存在时的对比值
	protected $validateModel;//验证模块
	protected $validateFunction;//验证函数名
	protected $validatedData;//验证后数据
	protected $uploadPath;//上传图片路径
	protected $initArray;//配置信息
	protected $isVal;//是否通过验证
	protected $isExists;//数据是否存在
	protected $isUpdateWhere;//数据库更新是否有条件
	protected $isTransaction;//是否开启事务
	protected $isRollBack;//开启回滚
	protected $validateErrorMessage;//验证错误信息
	protected $data;//传入原始数据
	protected $chlidColumns = array();//子表字段集
	protected $chlidColumnsValues;//子表字段集值
	
	function __construct($initArray,$serviceLocator)
	{
		$this->initArray = $initArray;
		$this->serviceLocator = $serviceLocator;
		$this->isTransaction = false;
		$this->isUpdateWhere = true;
		$this->events = new EventManager();
	}
	
	/**
	 * 写日志
	 * @param string $message
	 * @param int $line
	 */
	private function setLog($message,$line)
	{
		$this->events->trigger('setLog', null, array('model' => 'FormSubmit','message' => $message,'level' => 'WARN','fileName' => __FILE__,'line' => $line));
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
		$this->dbModel = $this->serviceLocator->get($initArray['dbModelName'])->dispatch($dbDispatchName);
		$validateDispatName === false ? $this->validateModel = $this->serviceLocator->get($initArray['validateModelName'])->dispatch($dbDispatchName) : $this->validateModel = $this->serviceLocator->get($initArray['validateModelName'])->dispatch($validateDispatName);
		$this->dbInsertFunction = $initArray['dbInsertFunction'];
		$this->insertExistsFunction = $initArray['insertExistsFunction'];
		$this->dbUpdateFunction = $initArray['dbUpdateFunction'];
		$this->updateExistsFunction = $initArray['updateExistsFunction'];
		$this->validateFunction = $initArray['validateFunction'];
	}
	
	/**
	 * 获取验证数据存在数据查询参数
	 * @param array $data
	 * @param array $existsParams
	 * @return array
	 */
	protected function getExistsArray($data,$existsParams)
	{
		$existsArray = array();
		foreach($existsParams as $param) {
			if(!isset($data[$param])) {
				$this->setLog('exists param not in validate',__LINE__);
				return false;
			}
			$existsArray[$param] = $data[$param];
		}
	
		return $existsArray;
	}
	
	/**
	 * 检查数据是否存在
	 * @param array $existsArray
	 * @return boolean
	 */
	protected function exists($existsArray)
	{
		$type = $this->type;
		$exists = false;
		if($type == 'insert') {
			$insertExistsFunction = $this->insertExistsFunction;
			method_exists($this->dbModel,$insertExistsFunction) === false && $this->setLog($insertExistsFunction.' method is undefined',__LINE__);
			$exists = $this->dbModel->$insertExistsFunction($existsArray);
		}
		else {
			$updateExistsFunction = $this->updateExistsFunction;
			method_exists($this->dbModel,$updateExistsFunction) === false && $this->setLog($updateExistsFunction.' method is undefined',__LINE__);
			$mainParams = $this->dbModel->$updateExistsFunction($existsArray);
			$mainParams !== false && $mainParams != current($this->updateExistsValue) && $exists = true;
		}

		return $exists;
	}
	
// 	protected function exists()
// 	{
// 		$type = $this->type;
// 		$exists = false;
// 		if($type == 'insert') {
			
// 		}
// 		else {
			
// 		}
// 	}
	
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
		$initArray = $this->initArray;
		trim((string)$type) == '' && $type == 'insert';
		if(!isset($initArray) || !is_array($params) || count($params) <= 0 || trim((string)$dbDispatchName) == '') return false;
		$this->data = $params;

		$this->setInfo($type,$initArray,$dbDispatchName,$validateDispatName);

		//触发验证前事件
		$this->events->trigger('FormSubmit/ValidateBefore',$this,array());
		//验证输入参数
		$validateFunction = $this->validateFunction;
		if(!method_exists($this->validateModel,$validateFunction)) {
			$this->setLog('validate function is undefined',__LINE__);
			return false;
		}
		$validation = $this->validateModel->$validateFunction($this->data);
		$this->isVal = true;
		if($validation === false) {
			$this->isVal = false;
			$this->validateErrorMessage = $this->validateModel->ErrorMessage();
			return false;
		}
		$this->validatedData = $this->validateModel->data;
		$this->uploadPath = $this->validateModel->uploadPath;
		if(!is_array($this->validatedData) || count($this->validatedData) <= 0) {
			$this->setLog('validate return data is empty',__LINE__);
			return false;
		}
		//触发验证后事件
		$this->events->trigger('FormSubmit/ValidateAfter',$this,array());

		//数据是否存在
		$exists = false;
		$this->isExists = false;
		if($existsParams !== false) {
			if(!is_array($existsParams) || count($existsParams) <= 0) {
				$this->setLog('exists params is not array or array is empty',__LINE__);
				return false;
			}
			//触发数据存在验证前事件
			$this->events->trigger('FormSubmit/ExistsBefore',$this,array());
			$existsArray = $this->getExistsArray($this->validatedData,$existsParams);
			if($existsArray === false) return false;
			$exists = $this->exists($existsArray);
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
				if(!method_exists($this->dbModel,$function)) {
					$this->setLog('insert function is undefined',__LINE__);
					return false;
				}
				$dbReturn = $this->dbModel->$function($validatedData);
				$this->lastInsertId = $this->dbModel->lastInsertId();
			}
			else {
				$function = $this->dbUpdateFunction;
				$this->isUpdateWhere === true ? $where = $this->updateExistsValue : $where = array();
				if(!method_exists($this->dbModel,$function)) {
					$this->setLog('update function is undefined',__LINE__);
					return false;
				}
				$dbReturn= $this->dbModel->$function($validatedData,$where);
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