<?php

namespace FormSubmit\Logic;

use FormSubmit\FormSubmit\BaseFormSubmit;

Class Update extends BaseFormSubmit
{	
	private $params;
	
	function __construct($initArray,$serviceLocator,$params)
	{
		$this->params = $params;
		parent::__construct($initArray,$serviceLocator);
	}
	
	/**
	 * 更新数据库
	 * @param array $params 更新参数
	 * @param array $updateExistsValue 数据更新条件|数据存在对比值
	 * @param array $existsParams 数据是否存在验证参数
	 * @param string $dbDispatchName 数据库操作模块分发名
	 * @param string $validateDispatName 验证模块分发名
	 * @return boolean
	 * 
	 * $existsParams为false时，不验证数据是否存在
	 */
	public function update($params,$updateExistsValue,$existsParams = false,$dbDispatchName,$validateDispatName = false)
	{
		if($params === false) {
			$params = $this->params;
		}
		$this->updateExistsValue = $updateExistsValue;
		$updateReturn = $this->formSubmit('update',$params,$existsParams,$dbDispatchName,$validateDispatName);
		if($updateReturn === false) return false;
	}
}