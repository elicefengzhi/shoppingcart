<?php

namespace FormSubmit\Logic;

use FormSubmit\FormSubmit\BaseFormSubmit;

Class Insert extends BaseFormSubmit
{	
	private $params;
	
	function __construct($initArray,$serviceLocator,$params)
	{
		$this->params = $params;
		parent::__construct($initArray,$serviceLocator);
	}
	
	/**
	 * 插入数据库
	 * @param array $params 插入参数
	 * @param array $existsParams 数据是否存在验证项
	 * @param string $dbDispatchName 数据库操作模块分发名
	 * @param string $validateDispatName 验证模块分发名
	 * @return boolean
	 * 
	 * $existsParams为false时，不验证数据是否存在
	 */
	public function insert($params,$existsParams = false,$dbDispatchName,$validateDispatName = false)
	{
		if($params === false) {
			$params = $this->params;
		}
		$insertReturn = $this->formSubmit('insert',$params,$existsParams,$dbDispatchName,$validateDispatName);
		if($insertReturn === false) return false;
	}
}