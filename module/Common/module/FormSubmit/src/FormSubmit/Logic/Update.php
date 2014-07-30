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
		try {
			return $this->formSubmit('update');
		}
		catch(\FormSubmit\Exception\FormSubmitException $e)
		{
			throw new \FormSubmit\Exception\FormSubmitException($e->getMessage());
		}
	}
}