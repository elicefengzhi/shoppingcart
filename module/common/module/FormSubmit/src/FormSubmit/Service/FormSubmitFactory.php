<?php
/**
 * 建立表单模块ServiceManager工厂
 * @author elice
 *
 */

namespace FormSubmit\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
//use FormSubmit\FormSubmit\FormSubmit;


class FormSubmitFactory implements FactoryInterface
{
	private $serviceLocator;
	private $initArray;
	private $requestType = 'post';
	
	function __call($className,$args)
	{
		$classNamespace = "\\FormSubmit\\Logic\\$className";
		if(class_exists($classNamespace) === false) throw new \Exception("formsubmit class $classNamespace undefined");
		
		//获得提交参数
		$request = new \Zend\Http\PhpEnvironment\Request;
		$this->requestType === 'post' && $params = $request->getPost()->toArray();
		$this->requestType === 'get' && $params = $request->getQuery()->toArray();
		//如果没有数据提交返回false
		if(count($params) <= 0) return false; 
		
		$formSubmit = new $classNamespace($this->initArray,$this->serviceLocator,$params);
		return $formSubmit;
	}
	
	public function setRequestType($type) {
		$type === 'post' ? $this->requestType = 'post' : $this->requestType = 'get';
	}
	
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$init = $serviceLocator->get('config');
    	isset($init['FormSubmit/init']) ? $initArray = $init['FormSubmit/init'] : $initArray = false;
    	$this->serviceLocator = $serviceLocator;
    	$this->initArray = $initArray;
        //return new FormSubmit($initArray,$serviceLocator);
        return $this;
    }
}
