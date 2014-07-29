<?php

namespace FormSubmit\Helper;

class BaseHelper
{
	protected $formSubmit;//formSubmit对象
	protected $extendsClassName;//helper子类名
	protected $extendsClassObject;//helper子类对象
	
	/**
	 * 设置formSubmit对象
	 * @param object $formSubmit
	 */
	public function setFormSubmit($formSubmit)
	{
		$this->formSubmit = $formSubmit;
	}
	
	/**
	 * 设置子类名
	 * @param string $className
	 */
	public function setClassName($className)
	{
		$this->extendsClassName = $className;
	}
	
	/**
	 * 设置子类对象
	 * @param object $classObject
	 */
	public function setClassObject($classObject)
	{
		$this->extendsClassObject = $classObject;
	}
	
	/**
	 * 注册可在formSubmit中调用的方法名
	 * @param string $functionName
	 */
	protected function registerFunction($functionName)
	{
		$this->formSubmit->helperObjectArray[$this->extendsClassName] = array();
		$this->formSubmit->helperObjectArray[$this->extendsClassName]['object'] = $this->extendsClassObject;
		$this->formSubmit->helperObjectArray[$this->extendsClassName]['functionName'] = $functionName;
	}
}