<?php

namespace FormSubmit\Model;

use FormSubmit\Model\BaseFormSubmit;

Class Insert extends BaseFormSubmit
{	
	private $method;
	
	function __construct($initArray,$serviceLocator,$method)
	{
		$this->method = $method;
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
			$request = new \Zend\Http\PhpEnvironment\Request;
			$this->method == 'post' && $params = $request->getPost()->toArray();
			$this->method == 'get' && $params = $request->getQuery()->toArray();
		}
		$insertReturn = $this->formSubmit('insert',$params,$existsParams,$dbDispatchName,$validateDispatName);
		if($insertReturn === false) return false;
	}
}