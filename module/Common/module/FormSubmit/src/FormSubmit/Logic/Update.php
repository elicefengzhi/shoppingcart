<?php

namespace FormSubmit\Logic;

use FormSubmit\Logic\Base;

Class Update extends Base
{	
	function __construct($requestData,$initArray,$serviceLocator)
	{
		$this->requestData = $requestData;
		$this->initArray = $initArray;
		$this->serviceLocator = $serviceLocator;
	}
	
	/**
	 * 执行更新表单提交
	 * @throws \Exception
	 * @return boolean
	 */
	public function submit()
	{
		return $this->formSubmit('update');
	}
}