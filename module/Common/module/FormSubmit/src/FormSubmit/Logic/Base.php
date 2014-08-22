<?php

namespace FormSubmit\Logic;

use FormSubmit\Logic\FormSubmit;
use FormSubmit\Media\MediaUpload;
 
Class Base
{
	protected $table;//表名或数据库操作对象
	protected $initArray;//配置信息
	protected $serviceLocator;
	protected $formSubmit;//formSubmit对象
	protected $media;//media对象
	protected $helper;//helper对象
	
	protected $requestData = false;//默认使用内部获取的request参数
	protected $where = false;//执行数据库操作时的where条件，默认没有
	protected $existsFields = false;//默认没有存在对比字段
	protected $existsWhere = false;//默认没有存在对比条件
	protected $isInsertExists = true;//默认进行添加数据重复验证
	protected $isUpdateExists = true;//默认进行更新数据重复验证
	protected $validateClass = false;//默认不验证
	protected $validateErrorMessage = false;//默认使用自带的错误提示语句
	protected $mediaIsMerge;//媒体上传后的地址是否合并入validatedData
	
	protected $isFilter = true;//默认过滤request参数
	protected $customFilter = false;//默认不进行自定义过滤
	protected $addField = false;//默认没有附加字段
	
	function __call($methodName,$arguments)
	{
		return $this->formSubmit->$methodName($arguments);
	}
	
	/**
	 * 附加字段
	 * @param array $Field
	 * @return \FormSubmit\Logic\Base
	 */
	public function addField(Array $Field)
	{
		$this->addField = $Field;
		return $this;
	}
	
	/**
	 * 表单插入数据
	 * 如果不传参，程序自动获取Request的Post(默认)或Get
	 *
	 * @param array $requestData
	 * @return \FormSubmit\Logic\Insert
	 */
	public function insert(Array $requestData = null)
	{
		!empty($requestData) && $this->requestData = $requestData;
		return $this;
	}
	
	/**
	 * 表单更新数据
	 * 如果不传参，程序自动获取Request的Post(默认)或Get
	 *
	 * @param array $requestData
	 * @return \FormSubmit\Logic\Insert
	 */
	public function update(Array $requestData = null)
	{
		!empty($requestData) && $this->requestData = $requestData;
		return $this;
	}
	
	/**
	 * 需要验证存在的字段
	 * 如果不传参则不验证
	 *
	 * @param array $existsFields
	 * @return \FormSubmit\Logic\Insert
	 */
	public function existsFields(Array $existsFields = null)
	{
		!empty($existsFields) && $this->existsFields = $existsFields;
		return $this;
	}
	
	/**
	 * 验证存在的字段条件
	 * 如果不传参则没有条件
	 * 
	 * @param array|object $existWhere
	 * @return \FormSubmit\Logic\FormSubmit
	 */
	public function existsWhere($existsWhere = false)
	{
		$existsWhere !== false && $this->existsWhere = $existsWhere;
		return $this;
	}
	
	/**
	 * 添加数据时是否执行重复数据验证
	 * @param boolean $isInsertExists
	 * @return \FormSubmit\Logic\Base
	 */
	public function isInsertExists($isInsertExists)
	{
		$this->isInsertExists = (bool)$isInsertExists;
		return $this;
	}
	
	/**
	 * 更新数据时是否执行重复数据验证
	 * @param boolean $isUpdateExists
	 * @return \FormSubmit\Logic\Base
	 */
	public function isUpdateExists($isUpdateExists)
	{
		$this->isUpdateExists = (bool)$isUpdateExists;
		return $this;
	}
	
	/**
	 * 是否验证request参数
	 * @param boolean $isFilter
	 * @return \FormSubmit\Logic\Base
	 */
	public function isFilter($isFilter)
	{
		$this->isFilter = (bool)$isFilter;
		return $this;
	}
	
	/**
	 * 自定义过滤
	 * @example<br/>
	 * 1. array('filter1' => 15 , 'filter2' => 2)<br/>
	 * 2. array('filter1' => 1 + 2 + 4 + 8 , 'filter2' => 4 + 8)<br/>
	 * 3. array('filter1' => \FormSubmit\Filter\Filter::HTMLENTITIES + \FormSubmit\Filter\Filter::STRINGTRIM)<br/>
	 * 解释：<br/>
	 * 不过滤 为 0<br/>
	 * 为"null"，则视为多余字段从request参数中注销<br/>
	 * STRINGTRIM 为 1<br/>
	 * STRIPTAGS 为 2<br/>
	 * HTMLENTITIES 为 4<br/>
	 * STRIPNEWLINES 为 8<br/>
	 * 
	 * @param array $customFilter
	 * @return \FormSubmit\Logic\Base
	 */
	public function customFilter(Array $customFilter)
	{
		$this->customFilter = $customFilter;
		return $this;
	}
	
	/**
	 * 执行操作的表名或数据操作对象
	 *
	 * @param string|object $tableName
	 * @return \FormSubmit\Logic\Insert
	 */
	public function table($table)
	{
		$this->table = $table;
		return $this;
	}
	
	/**
	 * 执行数据库操作时的where条件
	 * @param array|object $where
	 * @return \FormSubmit\Logic\Base
	 */
	public function where($where = false) {
		$where !== false && $this->where = $where;
		return $this;
	}
	
	/**
	 * 验证对象
	 * 如果不传参则不验证数据正确性
	 *
	 * @param object $validateClass
	 * @return \FormSubmit\Logic\Insert
	 */
	public function validate($validateClass = false)
	{
		$validateClass !== false && $this->validateClass = $validateClass;
		return $this;
	}
	
	/**
	 * 设置媒体上传对象
	 * @param object|array $media 如果是对象，则使用此对象
	 * @param boolean $isMergeValidatedData 媒体上传后的地址是否合并入validatedData
	 * @return \FormSubmit\Logic\Base
	 */
	public function mediaUpload($media = false,$isMergeValidatedData = true)
	{
		$init = array();
		$initArray = $this->initArray;
		$init['uploadPath'] = $GLOBALS['UPLOADPATH'];
		$init['minSize'] = $initArray['media']['minSize'];
		$init['maxSize'] = $initArray['media']['maxSize'];
		$init['mimeType'] = $initArray['media']['mimeType'];
		
		if(is_object($media)) {
			$this->media = $media;
			return $this;
		}
		else if(is_array($media)) {
			array_key_exists('uploadPath',$media) && $init['uploadPath'] = $media['uploadPath'];
			array_key_exists('minSize',$media) && $init['minSize'] = $media['minSize'];
			array_key_exists('maxSize',$media) && $init['maxSize'] = $media['maxSize'];
			array_key_exists('mimeType',$media) && $init['mimeType'] = $media['mimeType'];
		}
		$mediaUpload = new MediaUpload($init);
		$mediaUpload->setSourceValidateErrorMessage($this->validateErrorMessage);
		$this->media = $mediaUpload;
		$this->mediaIsMerge = $isMergeValidatedData;
		
		return $this;
	}
	
	/**
	 * helper的执行需要对应formSubmit主程序提供的具体事件为基础
	 * 
	 * 参数规则：
	 * 第一个参数：事件名(ValidateBefore、ValidateAfter、ExistsBefore、ExistsAfter、DbBefore、DbAfter)
	 * 第二个参数：helper类的类名
	 * 之后的参数任意，这些参数都会赋值给相应helper类的init方法
	 * 
	 * @return \FormSubmit\Logic\Base
	 */
	public function helper()
	{
		$args = func_get_args();
		$eventType = $args[0];
		$events = \Zend\EventManager\StaticEventManager::getInstance();
		//相应事件的逻辑
		$events->attach('*','FormSubmit/'.$eventType,function($event) use($args) {
			$helperType = $args[1];
			$initParams = $args;
			//去除第一个和第二个参数，以便把其余参数传入init方法中
			unset($initParams[0]);
			unset($initParams[1]);
			//获得formSubmit对象
			$target = $event->getTarget();
			$classNamespace = "\\FormSubmit\\Helper\\$helperType";
			//如果给定的类存在执行相应操作
			if(class_exists($classNamespace) === true) {
				//如果给定类名在之前应该生成过，则使用之前生成的对象，否则新生成
				$isExists = $target->helperObjectArrayisExistsByKey($helperType);
				if($isExists === true) {
					$formHelper = $target->getHelperObjectArrayByKey($helperType);
					$formHelper = $formHelper['object'];
				}
				else {
					$formHelper = new $classNamespace();
				}
				//把formSubmit对象传入helper
				$formHelper->setFormSubmit($target);
				//设置当前helper类名
				$formHelper->setClassName($helperType);
				//设置当前helper类
				$formHelper->setClassObject($formHelper);
				//执行相应helper类init方法
				call_user_func_array(array($formHelper,'init'),$initParams);
				//执行相应helper类action方法
				$formHelper->action();
			}
		});
		
		return $this;
	}
	
	/**
	 * 设置验证错误提示信息
	 * 
	 * 数组键名：
	 * 'maxSizeError' 媒体上传最大容量
	 * 'minSizeError' 媒体上传最小容量
	 * 'mimeTypeError' 媒体上传mime类型
	 * 'existsError' 数据存在
	 * @param array $validateErrorMessage
	 */
	public function setValidateErrorMessage(Array $validateErrorMessage)
	{
		$this->validateErrorMessage = $validateErrorMessage;
	}
	
	/**
	 * 执行表单提交主程序
	 * @param int $requestType
	 * @throws \Exception
	 * @return boolean
	 */
	public function formSubmit($requestType)
	{
		if(empty($this->table)) throw new \FormSubmit\Exception\FormSubmitException("formsubmit table unknow");
	
		$formSubmit = new FormSubmit($this->initArray,$this->serviceLocator);
		$this->formSubmit = $formSubmit;
		//如果不执行存在验证，则存在验证方法名赋值false
		if($requestType == 'insert') {
			$this->isInsertExists === false && $formSubmit->setInsertExistsFunction(false);
		}
		else {
			$this->isUpdateExists === false && $formSubmit->setupdateExistsSelect(false);
		}
		//设置是否过滤request参数
		$formSubmit->setIsFilter($this->isFilter);
		//设置自定义过滤request参数
		$formSubmit->setCustomFilter($this->customFilter);
		//设置附加字段
		$formSubmit->setAddField($this->addField);
		//设置媒体上传后的地址是否合并入validatedData
		$formSubmit->setMediaUpload($this->media,$this->mediaIsMerge);
		//设置验证错误提示信息
		$formSubmit->setSourceValidateErrorMessage($this->validateErrorMessage);
	
		$insertReturn = $formSubmit->formSubmit($requestType,$this->requestData,$this->table,$this->where,$this->existsFields,$this->existsWhere,$this->validateClass);
		return $insertReturn;
	}
}