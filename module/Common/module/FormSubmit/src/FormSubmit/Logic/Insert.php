<?php

namespace FormSubmit\Logic;

use FormSubmit\Logic\Base;

Class Insert extends Base
{	
	function __construct($requestData,$initArray,$serviceLocator)
	{
		$this->requestData = $requestData;
		$this->initArray = $initArray;
		$this->serviceLocator = $serviceLocator;
	}
	
	/**
	 * 执行添加表单提交
	 * @throws \Exception
	 * @return boolean
	 */
	public function submit()
	{
		if($this->requestData === false) return false;
		return $this->formSubmit('insert');
	}
}