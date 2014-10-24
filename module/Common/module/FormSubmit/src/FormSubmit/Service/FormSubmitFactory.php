<?php
/**
 * 建立表单模块ServiceManager工厂
 * @author elice
 *
 */

namespace FormSubmit\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

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
		$this->requestType === 'post' && $requestData = $request->getPost()->toArray();
		$this->requestType === 'get' && $requestData = $request->getQuery()->toArray();
		$this->requestType === 'cookie' && $requestData = $request->getCookie()->toArray();
		//如果没有数据提交返回false
		if(count($requestData) <= 0) return false; 

		$formSubmit = new $classNamespace($requestData,$this->initArray,$this->serviceLocator);
		return $formSubmit;
	}
	
	/**
	 * 设置request类型
	 * @param string $type post或get
	 */
	public function setRequestType($type) {
		$type === 'post' && $this->requestType = 'post';
		$type === 'get' && $this->requestType = 'get';
		$type === 'cookie' && $this->requestType = 'cookie';
	}
	
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$init = $serviceLocator->get('config');
    	isset($init['FormSubmit/init']) ? $initArray = $init['FormSubmit/init'] : $initArray = false;
    	$this->serviceLocator = $serviceLocator;
    	$this->initArray = $initArray;
        return $this;
    }
}
