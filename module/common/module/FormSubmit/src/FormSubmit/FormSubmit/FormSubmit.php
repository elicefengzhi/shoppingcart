<?php

namespace FormSubmit\FormSubmit;

class FormSubmit
{
	private $serviceLocator;
	private $initArray;
	
	function __construct($initArray,$serviceLocator)
	{
		!isset($initArray['submitMethod']) && $initArray['submitMethod'] = post;
		$this->initArray = $initArray;
		$this->serviceLocator = $serviceLocator;
	}
	
    /**
     * 表单提交类分发
     * @param string $type
     * @return boolean|object
     */
    public function dispatch($type)
    {
    	$request = new \Zend\Http\PhpEnvironment\Request;
    	$method = false;
    	count($request->getQuery()->toArray()) > 0 ? $method = 'get' : $request->isPost() &&  $method = 'post';
    	if(empty($type) || $method === false) return false;
    	 
    	$class = 'FormSubmit\Model\\'.$type;
    	return new $class($this->initArray,$this->serviceLocator,$method);
    }
}
