<?php
/**
 * 建立视图助手模块ServiceManager工厂
 * @author elice
 *
 */

namespace ViewHelper\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class ViewHelperFactory implements FactoryInterface
{
	private $serviceManager;
	
	function __call($className,$args)
	{
		$classNamespace = "\\ViewHelper\\Logic\\$className";
		if(class_exists($classNamespace) === false) throw new \Exception("viewhelper class $classNamespace undefined");
		$viewHelper = new $classNamespace();
		$viewHelper->setServiceManager($this->serviceManager);
		return $viewHelper;
	}
	
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceManager = $serviceLocator;
        return $this;
    }
}
