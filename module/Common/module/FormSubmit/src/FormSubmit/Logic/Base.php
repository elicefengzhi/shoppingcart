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
	protected $validateClass = null;//默认不验证
	protected $inputFilter = false;//默认不进行inputFilter
	protected $mediaIsMerge;//媒体上传后的地址是否合并入validatedData
	
    /**
	 * 如果调用的方法在本类不存在，则试图通过formSubmit对象调用相应方法
     */
	function __call($methodName,$arguments)
	{
		//如果formSubmit对象没有创建则创建，已经创建则调用该对象相应方法
		if(!is_object($this->formSubmit)) {
			$formSubmit = new FormSubmit($this->initArray,$this->serviceLocator);
			$this->formSubmit = $formSubmit;
		}
		$return = call_user_func_array(array($this->formSubmit,$methodName),$arguments);
		
		return is_null($return) ? $this : $return;
	}
	
	/**
	 * 设置RequestData数据<br/>
	 * 程序自动获取Request的Post(默认)或Get
	 * @param array $requestData
	 * @return \FormSubmit\Logic\Base
	 */
	public function requestData(Array $requestData = null)
	{
		!empty($requestData) && $this->requestData = $requestData;
		return $this;
	}
	
	/**
	 * 需要验证存在的字段<br/>
	 * 如果不传参则不验证，即使向existsWhere传参也不会验证
	 * @param array $existsFields
	 * @return \FormSubmit\Logic\Base
	 */
	public function existsFields(Array $existsFields = null)
	{
		!empty($existsFields) && $this->existsFields = $existsFields;
		return $this;
	}
	
	/**
	 * 验证存在的字段条件<br/>
	 * 如果不传参则没有条件<br/>
	 * @param boolean|Where|\Closure|string|array $existWhere
	 * @return \FormSubmit\Logic\Base
	 */
	public function existsWhere($existsWhere = false)
	{
		$existsWhere !== false && $this->existsWhere = $existsWhere;
		return $this;
	}
	
	/**
	 * 执行操作的表名或数据操作对象
	 * @param string|object $table
	 * @return \FormSubmit\Logic\Base
	 */
	public function table($table)
	{
		$this->table = $table;
		return $this;
	}
	
	/**
	 * 执行数据库操作时的where条件
	 * 如果不传参则没有条件<br/>
	 * @param boolean|Where|\Closure|string|array $where
	 * @return \FormSubmit\Logic\Base
	 */
	public function where($where = false) {
		$where !== false && $this->where = $where;
		return $this;
	}
	
	/**
	 * 验证对象<br/>
	 * 如果是对象，则作为验证对象调用<br/>
	 * 如果是布尔值，则视为是否通过验证<br/>
	 * 如果同时为validate和inputfilter赋值，以inputfilter优先<br/>
	 * @param object|boolean $validateClass
	 * @return \FormSubmit\Logic\Base
	 */
	public function validate($validateClass)
	{
		$this->validateClass = (!is_object($validateClass) && !is_bool($validateClass) && !is_null($validateClass)) ? false : $validateClass;
		return $this;
	}
	
	/**
	 * 如果同时为validate和inputfilter赋值，以inputfilter优先 
	 * @param array $inputFilter
	 * @return \FormSubmit\Logic\Base
	 */
	public function inputFilter(Array $inputFilter)
	{
		$this->inputFilter = $inputFilter;
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
		$init['uploadPath'] = $initArray['media']['uploadPath'];
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
	 * 第一个参数：事件名(ValidateBefore、ValidateAfter、InputFilterBefore、InputFilterAfter、ExistsBefore、ExistsAfter、DbBefore、DbAfter)
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
			//去除"事件名"和"helper类名"参数，以便把其余参数传入init方法中
			unset($initParams[0]);
			unset($initParams[1]);
			//获得formSubmit对象
			$target = $event->getTarget();
			$classNamespace = "\\FormSubmit\\Helper\\$helperType";
			//如果给定的类存在执行相应操作
			if(class_exists($classNamespace) === true) {
				//如果给定类名已经在formSubmit中注册过，则使用此对象，否则新生成
				$isExists = $target->helperObjectArrayisExistsByKey($helperType);
				if($isExists === true) {
					$formHelper = $target->getHelperObjectArrayByKey($helperType);
					$formHelper = $formHelper['object'];
				}
				else {
					$formHelper = new $classNamespace();
					//把formSubmit对象传入helper
					$formHelper->setFormSubmit($target);
					//设置当前helper类名
					$formHelper->setClassName($helperType);
					//设置当前helper类
					$formHelper->setClassObject($formHelper);
				}
				//执行相应helper类init方法
				call_user_func_array(array($formHelper,'init'),$initParams);
				//执行相应helper类action方法
				$formHelper->action();
			}
		});
		
		return $this;
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
	
		$formSubmit = !is_object($this->formSubmit) ? new FormSubmit($this->initArray,$this->serviceLocator) : $this->formSubmit;

		//设置媒体上传后的地址是否合并入validatedData
		$formSubmit->mediaUpload($this->media,$this->mediaIsMerge);
	
		$insertReturn = $formSubmit->formSubmit($requestType,$this->requestData,$this->table,$this->where,$this->existsFields,$this->existsWhere,$this->validateClass,$this->inputFilter);
		return $insertReturn;
	}
}