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
	protected $validateFunction = false;//验证方法名
	protected $validateErrorMessage;//错误提示语句，默认使用自带的错误提示语句
	protected $sourceValidateErrorMessage  = false;//原始验证错误信息，默认使用自带的错误提示语句
	protected $validateErrorMessageFunction = false;//验证错误信息方法名
	
	protected $isFilter = true;//是否过滤request参数
	protected $customFilter = false;//自定义request参数过滤，默认不进行自定义过滤
	protected $addField = false;//附加字段，默认过滤request参数
	protected $isCustomExists = false;//是否自定义数据验证
	protected $isExists;//request数据是否已经存在
	protected $isTransaction;//是否开启事务
	protected $isRollBack;//开启回滚
	protected $isVal;//是否通过验证
	
	protected $dbInsertFunction = null;//添加方法名
	protected $insertExistsFunction = null;//添加存在验证方法名
	
	protected $isUpdateWhere;//数据库更新是否有条件
	protected $dbUpdateFunction = null;//更新方法名 
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
		//如果没有给dbInsertFunction额外赋值，使用配置中的值
		is_null($this->dbInsertFunction) && $this->dbInsertFunction = $initArray['db']['dbInsertFunction'];
		//如果existsParams为false，则不执行添加存在验证
		//如果没有给insertExistsFunction额外赋值，使用配置中的值
		$existsParams === false ? $this->insertExistsFunction = false : (is_null($this->insertExistsFunction) && $this->insertExistsFunction = $initArray['db']['insertExistsFunction']);
		//如果没有给dbUpdateFunction额外赋值，使用配置中的值
		is_null($this->dbUpdateFunction) && $this->dbUpdateFunction = $initArray['db']['dbUpdateFunction'];
		//如果existsParams为false，则不执行更新存在验证
		$existsParams === false ? $this->updateExistsFunction = false : $this->updateExistsFunction = $initArray['db']['updateExistsFunction'];
		
		//如果不是null，则需要获得验证方法名等以便进行验证
		if(!is_null($this->validateClass)) {
			$this->validateFunction === false && $this->validateFunction = $initArray['validate']['validateFunction'];
			$this->validateErrorMessageFunction === false && $this->validateErrorMessageFunction = $initArray['validate']['errorMessageFunction'];
		}
	}
	
	/**
	 * 对函数任意传参时的处理
	 * @param string $function
	 * @param array $args
	 */
	private function functionAgrs($function,$args)
	{
		if(count($args > 0)) {
			//赋值函数名
			$this->$function = $args[0];
			//注销函数名数组元素
			unset($args[0]);
			//如果还有其余数组元素，全部作为函数参数处理
			count($args) > 0 && $this->$function = array('name'=> $this->$function , 'args' => $args);
		}
	}
	
	/**
	 * 主程序执行数据验证
	 * @param boolean|object $validateClass
	 * @param array $requestData
	 * @throws \FormSubmit\Exception\FormSubmitException
	 * @return boolean
	 */
	private function formSubmitValidate($validateClass,$requestData)
	{
		//触发验证前事件
		$this->events->trigger('FormSubmit/ValidateBefore',$this,array());

		//如果validateClass不是null，则需要进行验证
		if(!is_null($this->validateClass)) {
			//验证输入参数
			//如果validateClass为布尔值，此参数直接作为是否通过验证参数
			if(is_bool($this->validateClass)) {
				$validation = $this->validateClass;
			}
			else {
				$validateFunction = $this->validateFunction;
				//如果$validateFunction是数组，则需要在调用时对其传参	
				$validateFunctionName = is_array($validateFunction) ? $validateFunction['name'] : $validateFunction;
				if(!method_exists($this->validateClass,$validateFunctionName)) {
					throw new \FormSubmit\Exception\FormSubmitException('validate function is undefined');
					return false;
				}
				$validation = is_array($validateFunction) ? call_user_func_array(array($this->validateClass,$validateFunction['name']),$validateFunction['args']) : $this->validateClass->$validateFunction($requestData);
			}
			
			$this->isVal = true;
			if($validation === false) {
				$this->isVal = false;
				$validateErrorMessageFunction = $this->validateErrorMessageFunction;
				if(!method_exists($this->validateClass,$validateErrorMessageFunction)) {
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
	}
	
	private function formSubmitInputFilter($requestData,$inputFilter)
	{
		//触发inputFilter前事件
		$this->events->trigger('FormSubmit/InputFilterBefore',$this,array());

		//如果inputFilter不为false，则需要执行inputFilter操作
		if($this->inputFilter !== false) {
			$inputFilterClass = new \FormSubmit\InputFilter\InputFilter();
			$this->isVal = $inputFilterClass->isVal($requestData,$inputFilter);
			
			if($this->isVal === false) {
				$this->validateErrorMessage = $inputFilterClass->getMessage();
				return false;
			}
			
			$this->validatedData = $inputFilterClass->getValues();
			if(!is_array($this->validatedData) || count($this->validatedData) <= 0) {
				throw new \FormSubmit\Exception\FormSubmitException('inputFilter return data is empty');
				return false;
			}
		}

		//触发inputFilter后事件
		$this->events->trigger('FormSubmit/InputFilterAfter',$this,array());
	}
	
	/**
	 * 主程序媒体上传
	 * @return boolean
	 */
	private function formSubmitMediaUpload()
	{
		if(is_object($this->mediaUpload)) {
			$request = new \Zend\Http\PhpEnvironment\Request();
			$files = $request->getFiles()->toArray();
			if(count($files) > 0) {
				$uploadReturn = $this->mediaUpload->upload($files);
				if(!is_null($uploadReturn)) {
					$this->uploadedPath = $uploadReturn;
					$mediaErrorMessage = $this->mediaUpload->getValidateErrorMessage();
					if(count($mediaErrorMessage) > 0) {
						$this->isVal = false;
						is_array($this->validateErrorMessage) ? $this->validateErrorMessage = array_merge($this->validateErrorMessage,$mediaErrorMessage) : $this->validateErrorMessage = $mediaErrorMessage;
						return false;
					}
					//如果mdediaIsMerge为ture，则合并上传后的媒体地址进入validatedData
					$this->mediaIsMerge === true && $this->validatedData = array_merge($this->validatedData,$this->uploadedPath);
				}
			}
		}
	}
	
	/**
	 * 主程序数据存在验证
	 * @param string $requestType
	 * @param string|object $table
	 * @param array|object $where
	 * @param array $existsParams
	 * @param array|object $existsWhere
	 * @throws \FormSubmit\Exception\FormSubmitException
	 * @return boolean
	 */
	private function formSubmitExists($requestType,$table,$where,$existsParams,$existsWhere)
	{
		$exists = false;
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
				$existsValidate->setAdapter($this->serviceLocator->get('FormSubmit/adapter'));
				$exists = $existsValidate->existsValidate($tableName,$where,$existsField,$existsWhere);
			}
			else {
				$exists = $this->customExists($existsField,$existsWhere);
			}
		
			if($exists === true) {
				$existsErrorMessage = array();
				//如果没有动态改变验证错误信息则使用ErrorMessage.php内定义的信息
				$this->sourceValidateErrorMessage === false ? $sourceErrorMessage = include __DIR__.'/../ErrorMessage/ErrorMessage.php' : $sourceErrorMessage = $this->sourceValidateErrorMessage;
				foreach($existsParams as $param) {
					$existsErrorMessage[$param]['existsError'] = $sourceErrorMessage['existsError'];
				}
				is_array($this->validateErrorMessage) ? $this->validateErrorMessage = array_merge($this->validateErrorMessage,$existsErrorMessage) : $this->validateErrorMessage = $existsErrorMessage;
			}
			//触发数据存在验证后事件
			$this->events->trigger('FormSubmit/ExistsAfter',$this,array());
		}
		$this->isExists = $exists;
	}
	
    /**
     * 主函数数据库操作
     * @param string $requestType
     * @param string|object $where
     * @throws \FormSubmit\Exception\FormSubmitException
     * @return boolean|unknown
     */
	private function forSubmitDataBase($requestData,$requestType,$where)
	{
		if($this->isExists === false) {
			//触发数据库操作前事件
			$this->events->trigger('FormSubmit/DbBefore',$this,array());
			//如果insert或update方法给定了数据，使用该数据
			$validatedData = is_null($this->validatedData) ? $requestData : $this->validatedData;
			//是否开启事务
			$this->isTransaction === true && $this->dbModel->beginTransaction();
			//与附加字段合并
			$this->addField !== false && $validatedData = array_merge($validatedData,$this->addField);
			//执行插入或更新
			if($requestType == 'insert') {
				$function = $this->dbInsertFunction;
				if($this->tableName !== false) {
					//如果是程序自动插入，则调用函数执行插入
					$sql = new \FormSubmit\DbSql\DbSql($this->serviceLocator->get('FormSubmit/adapter'),$this->tableName);
					$dbReturn = $sql->insert($validatedData,$where);
					if($dbReturn !== false) {
						$this->lastInsertId = $dbReturn;
						$dbReturn = true;
					}
				}
				else {
					//如果传人的值是字符串则用此字符串作为待调用的函数名，如果是数组，则使用name元素的值作为待调用的函数名
					$functionName = is_array($function) ? $function['name'] : $function;
					if(!method_exists($this->dbModel,$functionName)) {
						throw new \FormSubmit\Exception\FormSubmitException($functionName.' insert function is undefined');
						return false;
					}
					//如果包含额外参数，保证传入函数的第一个参数为验证后的requestData，后面的参数为额外参数
					$args = array();
					$args[0] = $validatedData;
					is_array($function) && $args = array_merge($args,$function['args']);
					
					$dbReturn = is_array($function) ? call_user_func_array(array($this->dbModel,$functionName),$args) : $this->dbModel->$functionName($validatedData);
					$dbReturn = (boolean)$dbReturn;
					method_exists($this->dbModel,'lastInsertId') && $this->lastInsertId = $this->dbModel->lastInsertId();
					unset($args);
				}
			}
			else {
				$function = $this->dbUpdateFunction;
				if($this->tableName !== false) {
					//如果是程序自动更新，则调用函数执行更新
					$sql = new \FormSubmit\DbSql\DbSql($this->serviceLocator->get('FormSubmit/adapter'),$this->tableName);
					$dbReturn = $sql->update($validatedData,$where);
				}
				else {
					//如果传人的值是字符串则用此字符串作为待调用的函数名，如果是数组，则使用name元素的值作为待调用的函数名
					$functionName = is_array($function) ? $function['name'] : $function;
					if(!method_exists($this->dbModel,$functionName)) {
						throw new \FormSubmit\Exception\FormSubmitException($functionName.' update function is undefined');
						return false;
					}
					//如果包含额外参数，保证传入函数的第一个参数为验证后的requestData、第二个参数为更新的条件，后面的参数为额外参数
					$args = array();
					$args[0] = $validatedData;
					$args[1] = $where;
					is_array($function) && $args = array_merge($args,$function['args']);

					$dbReturn = is_array($function) ? call_user_func_array(array($this->dbModel,$functionName),$args) : $this->dbModel->$functionName($validatedData,$where);
					$dbReturn = (boolean)$dbReturn;
					unset($args);
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
			$functionName = is_array($insertExistsFunction) ? $insertExistsFunction['name'] : $insertExistsFunction;
			$args = array();
			$args[0] = $existsField;
			$args[1] = $existsWhere;
			$args = array_merge($args,$insertExistsFunction['args']);
			
			if(method_exists($this->dbModel,$functionName) === false) throw new \FormSubmit\Exception\FormSubmitException($functionName.' method is undefined');
			$exists = is_array($insertExistsFunction) ? call_user_func_array(array($this->dbModel,$functionName),$args) : $this->dbModel->$functionName($existsField,$existsWhere);
			$exists = (boolean)$exists;
		}
		else {
			$updateExistsFunction = $this->updateExistsFunction;
			$functionName = is_array($updateExistsFunction) ? $updateExistsFunction['name'] : $updateExistsFunction;
			$args = array();
			$args[0] = $existsField;
			$args[1] = $existsWhere;
			$args = array_merge($args,$updateExistsFunction['args']);
			
			if(method_exists($this->dbModel,$functionName) === false) throw new \FormSubmit\Exception\FormSubmitException($functionName.' method is undefined');
			$mainParams = is_array($insertExistsFunction) ? call_user_func_array(array($this->dbModel,$functionName),$args) : $this->dbModel->$functionName($existsField,$existsWhere);
			$mainParams !== false && $mainParams != current($this->updateExistsValue) && $exists = true;
		}

		return $exists;
	}
	
	/**
	 * 表单提交模块主函数
	 * @param string $requestType
	 * @param array $requestData
	 * @param string|object $table
	 * @param array|object $where
	 * @param array $existsParams
	 * @param array|object $existsWhere
	 * @param boolean|object $validateClass
	 * @param boolean|array $inputFilter
	 * @throws \FormSubmit\Exception\FormSubmitException
	 * @return boolean
	 */
	public function formSubmit($requestType,$requestData,$table,$where,$existsParams,$existsWhere,$validateClass,$inputFilter)
	{
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

		//Request参数验证
		if(is_array($inputFilter)) {
			if($this->formSubmitInputFilter($requestData,$inputFilter) === false) return false;
		}
		else {
			if($this->formSubmitValidate($validateClass,$requestData) === false) return false;
		}

		//媒体上传
		if($this->formSubmitMediaUpload() === false) return false;
		
		//数据是否存在
		if($this->formSubmitExists($requestType,$table,$where,$existsParams,$existsWhere) === false) return false;

		//数据库操作
		return $this->forSubmitDataBase($requestData,$requestType,$where);
	}
	
	/**
	 * 设置插入函数名及额外参数
	 * @param string $dbInsertFunction
	 */
	public function dbInsertFunction($dbInsertFunction)
	{
		$args = func_get_args();
		$this->functionAgrs('dbInsertFunction',$args);
	}
	
	/**
	 * 设置插入数据存在检查函数名
	 * @param string $insertExistsFunction
	 */
	public function insertExistsFunction($insertExistsFunction)
	{
		$args = func_get_args();
		$this->functionAgrs('insertExistsFunction',$args);
		//如果给该函数传值，则自动开启自定义存在验证
		$this->isCustomExists = true;
	}
	
	/**
	 * 设置更新函数名
	 * @param string $dbUpdateFunction
	 */
	public function dbUpdateFunction($dbUpdateFunction)
	{
		$args = func_get_args();
		$this->functionAgrs('dbUpdateFunction',$args);
	}
	
	/**
	 * 设置更新数据存在检查函数名
	 * @param string $insertExistsFunction
	 */
	public function updateExistsFunction($updateExistsFunction)
	{
		$args = func_get_args();
		$this->functionAgrs('updateExistsFunction',$args);
		//如果给该函数传值，则自动开启自定义存在验证
		$this->isCustomExists = true;
	}
	
	/**
	 * 设置验证函数名<br/>
	 * 第一个参数为调用的函数名，其余参数为函数的参数(可以没有)
	 */
	public function validateFunction()
	{
		$args = func_get_args();
		$this->functionAgrs('validateFunction',$args);
	}
	
	/**
	 * 设置验证错误信息函数
	 * @param string $validateErrorMessageFunction
	 */
	public function validateErrorMessageFunction($validateErrorMessageFunction)
	{
		$this->validateErrorMessageFunction = $validateErrorMessageFunction;
	}
	
	/**
	 * 设置数据库更新是否有条件
	 * @param boolean $isUpdateWhere
	 */
	public function isUpdateWhere($isUpdateWhere)
	{
		$this->isUpdateWhere = (bool)$isUpdateWhere;
	}
	
	/**
	 * 设置是否过滤request参数<br/>
	 * 默认进行stringTrim+stripTags+htmlEntities+stripNewLines验证<br/>
	 * 如果不需要验证，则一定要明确对此函数赋值
	 * @param boolean $isFilter
	 */
	public function isFilter($isFilter)
	{
		$this->isFilter = (bool)$isFilter;
	}
	
	/**
	 * 自定义过滤
	 * @example<br/>
	 * 1. array('filter1' => 15 , 'filter2' => 2)<br/>
	 * 2. array('filter1' => 1 + 2 + 4 + 8 , 'filter2' => 4 + 8)<br/>
	 * 3. array('filter1' => \FormSubmit\Filter\Filter::HTMLENTITIES + \FormSubmit\Filter\Filter::STRINGTRIM)
	 * 
	 * 解释：<br/>
	 * STRINGTRIM 为 1<br/>
	 * STRIPTAGS 为 2<br/>
	 * HTMLENTITIES 为 4<br/>
	 * STRIPNEWLINES 为 8<br/>
	 * 
	 * 如果对某字段不过滤，则赋值空('')<br/>
	 * 为"null"，则视为多余字段从request参数中注销<br/>
	 * 
	 * @param array $customFilter
	 * @return \FormSubmit\Logic\Base
	 */
	public function customFilter(Array $customFilter)
	{
		$this->customFilter = $customFilter;
	}
	
	/**
	 * 设置附加字段
	 * @param array $addField
	 */
	public function addField(Array $addField)
	{
		$this->addField = $addField;
	}
	
	/**
	 * 设置是否开启事务
	 * @param boolean $isTransaction
	 */
	public function isTransaction($isTransaction)
	{
		$this->isTransaction = $isTransaction;
	}
	
	/**
	 * 设置验证后数据
	 * @param array $data
	 */
	public function validatedData($data)
	{
		$this->validatedData = $data;
	}
	
	/**
	 * 设置验证错误提示信息
	 * 
	 * 数组键名：
	 * 'maxSizeError' 媒体上传最大容量
	 * 'minSizeError' 媒体上传最小容量
	 * 'mimeTypeError' 媒体上传mime类型
	 * 'existsError' 数据存在
	 * @param array $sourceValidateErrorMessage
	 */
	public function sourceValidateErrorMessage(Array $sourceValidateErrorMessage)
	{
		$this->sourceValidateErrorMessage = $sourceValidateErrorMessage;
	}
	
	/**
	 * 设置媒体上传对象
	 * @param object $mediaUpload
	 */
	public function mediaUpload($mediaUpload,$mediaIsMerge)
	{
		$this->mediaUpload = $mediaUpload;
		$this->mediaIsMerge = $mediaIsMerge;
	}
	
	/**
	 * 设置回滚
	 * @param boolean $isRollBack
	 */
	public function isRollBack($isRollBack)
	{
		$this->isRollBack = $isRollBack;
	}
	
	/**
	 * 设置是否自定义数据验证
	 * @param boolean $isCustomExists
	 */
	public function isCustomExists($isCustomExists)
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