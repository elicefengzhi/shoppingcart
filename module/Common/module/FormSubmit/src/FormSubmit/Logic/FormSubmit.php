<?php

namespace FormSubmit\Logic;

use Zend\EventManager\EventManager;
use FormSubmit\Validate\OriginalParamsValidate;
use FormSubmit\Validate\Validate;
use FormSubmit\Filter\Filter;

Class FormSubmit
{
	protected $initArray;//配置信息
	protected $serviceLocator;//serviceLocator
	
	protected $tableName;//操作表名
	protected $lastInsertId;//添加数据的自增ID
	protected $validatedData;//验证后的request参数
	protected $sourceData;//原始的request参数
	protected $uploadedPath;//上传好的文件路径
	
	protected $validateClass;//验证对象
	protected $validateErrorMessage;//验证错误信息
	protected $validateErrorMessageFunction;//验证错误信息方法名
	
	protected $isFilter;//是否过滤request参数
	protected $customFilter;//自定义request参数过滤
	protected $addField;//附加字段
	protected $isCustomExists;//是否自定义数据验证
	protected $isExists;//request数据是否已经存在
	protected $isTransaction;//是否开启事务
	protected $isRollBack;//开启回滚
	protected $isVal;//是否通过验证
	
	protected $dbInsertFunction;//添加方法名
	protected $insertExistsFunction;//添加存在验证方法名
	
	protected $isUpdateWhere;//数据库更新是否有条件
	protected $dbUpdateFunction;//更新方法名 
	protected $updateExistsFunction;//更新存在验证方法名 
	
	protected $mediaUpload;//媒体上传对象
	protected $mediaIsMerge;//设置媒体上传后的地址是否合并入validatedData
	public $helperObjectArray = array();//helper对象保持数组
	
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
		$uploadedPath = $this->uploadedPath;
		if($type == 'insert' && is_array($uploadedPath) && count($uploadedPath) > 0) {
			foreach($uploadedPath as $file) {
				is_file($file) && @unlink($file);
			}
		}
	}
	
	/**
	 * 验证数据存在数据查询参数
	 * @param array $data
	 * @param array $existsParams
	 * @return array
	 */
	protected function getExistsField($data,$existsParams)
	{
		$existsArray = array();
		foreach($existsParams as $param) {
			if(!array_key_exists($param,$data)) {
				throw new \FormSubmit\Exception\FormSubmitException("exists param not in validate");
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
	protected function customExists($existsField,$existsWhere)
	{
		$type = $this->requestType;
		$exists = false;
		if($type == 'insert') {
			$insertExistsFunction = $this->insertExistsFunction;
			if(method_exists($this->dbModel,$insertExistsFunction) === false) throw new \FormSubmit\Exception\FormSubmitException($insertExistsFunction.' method is undefined');
			$exists = $this->dbModel->$insertExistsFunction($existsField,$existsWhere);
		}
		else {
			$updateExistsFunction = $this->updateExistsFunction;
			if(method_exists($this->dbModel,$updateExistsFunction) === false) throw new \FormSubmit\Exception\FormSubmitException($updateExistsFunction.' method is undefined');
			$mainParams = $this->dbModel->$updateExistsFunction($existsField,$existsWhere);
			$mainParams !== false && $mainParams != current($this->updateExistsValue) && $exists = true;
		}

		return $exists;
	}
	
	/**
	 * 设置参数
	 * @param string $requestType
	 * @param array $requestData
	 * @param array $initArray
	 * @param string|object $table
	 * @param object $validateClass
	 * @param array $existsParams
	 */
	private function setParams($requestType,$requestData,$initArray,$table,$validateClass,$existsParams)
	{
		//设置request的类型(post,get)
		$this->requestType = $requestType;
		//设置原始request数据
		$this->sourceData = $requestData;
		//如果table参数是对象，对dbModel赋值，程序将使用此对象进行数据库操作，否则把此参数视作需要操作的表名
		is_object($table) ? $this->dbModel = $table : $this->tableName = trim((string)$table);
		
		$this->validateClass = $validateClass;
		$this->dbInsertFunction = $initArray['db']['dbInsertFunction'];
		//如果existsParams为false，则不执行添加存在验证
		$existsParams === false ? $this->insertExistsFunction = false : $this->insertExistsFunction = $initArray['db']['insertExistsFunction'];
		$this->dbUpdateFunction = $initArray['db']['dbUpdateFunction'];
		//如果existsParams为false，则不执行更新存在验证
		$existsParams === false ? $this->updateExistsFunction = false : $this->updateExistsFunction = $initArray['db']['updateExistsFunction'];
		$this->validateFunction = $initArray['validate']['validateFunction'];
		$this->validateErrorMessageFunction = $initArray['validate']['errorMessageFunction'];
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
	public function formSubmit($requestType,$requestData,$table,$where,$existsParams,$existsWhere,$validateClass)
	{
		$dbReturn = true;//返回值初始化
		$initArray = $this->initArray;//获得配置信息

		//原始参数验证
		$originalParams = new OriginalParamsValidate();
		$isVal = $originalParams->validate($initArray,$requestData,$table,$validateClass);
		if($isVal === false) {
		    throw new \FormSubmit\Exception\FormSubmitException('source params is error');
		    return false;
		}

		//设置参数初始值
		$this->setParams($requestType,$requestData,$initArray,$table,$validateClass,$existsParams);

		//触发验证前事件
		$this->events->trigger('FormSubmit/ValidateBefore',$this,array());
		if($validateClass !== false) {
			//验证输入参数
			$validateFunction = $this->validateFunction;
			if(!method_exists($this->validateClass,$validateFunction)) {
				throw new \FormSubmit\Exception\FormSubmitException('validate function is undefined');
				return false;
			}
			$validation = $this->validateClass->$validateFunction($requestData);
			$this->isVal = true;
			if($validation === false) {
				$this->isVal = false;
				$validateErrorMessageFunction = $this->validateErrorMessageFunction;
				if(!method_exists($this->validateClass,validateErrorMessageFunction)) {
					throw new \FormSubmit\Exception\FormSubmitException('validate errorMessage function is undefined');
				}
				else {
					$this->validateErrorMessage = $this->validateClass->$validateErrorMessageFunction();
				}
				return false;
			}
			//验证通过后根据isFilter过滤参数
			if($this->isFilter) {
				$filter = new Filter();
				$this->validatedData = $filter->filterData($requestData,$this->customFilter);
			} 
			else {
				$this->validatedData = $requestData;
			}
			if(!is_array($this->validatedData) || count($this->validatedData) <= 0) {
				throw new \FormSubmit\Exception\FormSubmitException('validate return data is empty');
				return false;
			}
		}
		//触发验证后事件
		$this->events->trigger('FormSubmit/ValidateAfter',$this,array());

		if(is_object($this->mediaUpload)) {
			$request = new \Zend\Http\PhpEnvironment\Request();
			$files = $request->getFiles()->toArray();
			if(count($files) > 0) {
				$this->uploadedPath = $this->mediaUpload->upload($files);
				//如果mdediaIsMerge为ture，则合并上传后的媒体地址进入validatedData
				$this->mediaIsMerge === true && $this->validatedData = array_merge($this->validatedData,$this->uploadedPath);
			}
		}
		
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
			//获得需验证字段信息
			$existsField = $this->getExistsField($this->validatedData,$existsParams);
			if($existsField === false) {
				throw new \FormSubmit\Exception\FormSubmitException('exists params undefined in validatedData');
				return false;
			}
			//判断是否执行用户自定义数据存在验证方法
			if($this->isCustomExists === false) {
			    //如果没有提供数据操作表名，通过调用DbSql基础类“getTableName”函数取得表名
			    $this->tableName === false ? $tableName = $this->dbModel->getTableName() : $tableName = $this->tableName;
			    $existsValidate = new Validate();
			    $existsValidate->setRequestType($requestType);
			    $existsValidate->setAdapter($this->serviceLocator->get('adapter'));
			    $exists = $existsValidate->existsValidate($tableName,$where,$existsField,$existsWhere);
			}
			else {
			    $exists = $this->customExists($existsField,$existsWhere);
			}
			$this->isExists = $exists;
			//触发数据存在验证后事件
			$this->events->trigger('FormSubmit/ExistsAfter',$this,array());
		}

		//数据库操作
		if($exists === false) {
			//触发数据库操作前事件
			$this->events->trigger('FormSubmit/DbBefore',$this,array());
			$validatedData = $this->validatedData;

			//是否开启事务
			$this->isTransaction === true && $this->dbModel->beginTransaction();
			//与附加字段合并
			$this->addField !== false && $validatedData = array_merge($validatedData,$this->addField);
			//执行插入或更新
			if($requestType == 'insert') {
				$function = $this->dbInsertFunction;
				if($this->tableName !== false) {
				    //如果是程序自动插入，则调用函数执行插入
					$sql = new \FormSubmit\DbSql\DbSql($this->serviceLocator->get('adapter'),$this->tableName);
					$dbReturn = $sql->insert($validatedData);
					if($dbReturn !== false) {
						$this->lastInsertId = $dbReturn;
						$dbReturn = true;
					}
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
			if($dbReturn === true) {
				$this->isTransaction === true && $this->dbModel->commit();
			}
			else if($this->isTransaction === true || $this->isRollBack === true) {
				$this->dbModel->rollback() && $this->rollBackUpload($requestType);
			}

			return $dbReturn;
		}
		
		return false;
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
	 * 设置是否过滤request参数
	 * @param boolean $isFilter
	 */
	public function setIsFilter($isFilter)
	{
		$this->isFilter = $isFilter;
	}
	
	/**
	 * 设置自定义过滤request参数
	 * @param array $customFilter
	 */
	public function setCustomFilter($customFilter)
	{
		$this->customFilter = $customFilter;
	}
	
	/**
	 * 设置附加字段
	 * @param array $addField
	 */
	public function setAddField($addField)
	{
		$this->addField = $addField;
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
	 * 设置媒体上传对象
	 * @param object $mediaUpload
	 */
	public function setMediaUpload($mediaUpload,$mediaIsMerge)
	{
		$this->mediaUpload = $mediaUpload;
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
	 * 获得所有媒体上传路径
	 */
	public function getUploadedPath()
	{
		return $this->uploadedPath;
	}
	
	/**
	 * 执行相应helper对象方法
	 * @param stirng $className helper对象名
	 * @param string $functionName helper方法名
	 * @return mixed|boolean
	 */
	public function getHelperFunction($className,$functionName)
	{
		if(class_exists('FormSubmit\\Helper\\'.$className)) {
			if(method_exists('FormSubmit\\Helper\\'.$className,$functionName)) {
				$args = func_get_args();
				unset($args[0]);
				unset($args[1]);
				$helper = $this->helperObjectArray[$className];
				return call_user_func_array(array($helper['object'],$functionName),$args);
			}
		}
		
		return false;
	}
	
	/**
	 * 制定键名的helper对象是否存在
	 * @param stirng $key
	 * @return boolean
	 */
	public function helperObjectArrayisExistsByKey($key)
	{
		return array_key_exists($key,$this->helperObjectArray);
	}
	
	/**
	 * 通过键名获得helper对象
	 * @param string $key
	 * @return boolean|object
	 */
	public function getHelperObjectArrayByKey($key)
	{
		return array_key_exists($key,$this->helperObjectArray) ? $this->helperObjectArray[$key] : false;
	}
	
	/**
	 * 获得原始数据
	 * @return array
	 */
	public function getSourceData()
	{
		return $this->sourceData;
	}
	
	/**
	 * 获得最后插入id
	 */
	public function getLastInsertId()
	{
		return $this->lastInsertId;
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