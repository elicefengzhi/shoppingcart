<?php
/**
 * 建立验证模块ServiceManager工厂
 * @author elice
 *
 */

namespace Validate\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ValidateFactory implements FactoryInterface
{
	private $imageUpload;
	private $adapter;
	
	function __call($className,$args)
	{
		$className != 'QuickValidate' ? $classNamespace = "\\Validate\\Logic\\$className" : $classNamespace = '\\Validate\\Validate\\QuickValidate';
		if(class_exists($classNamespace) === false) throw new \Exception("validate class $classNamespace undefined");
		$Vaildate = new $classNamespace();
		$Vaildate->init($this->imageUpload,$this->adapter,$Vaildate);
		return $Vaildate;
	}
	
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$serviceLocator->has('ImageUpload') ? $imageUploadModule = $serviceLocator->get('ImageUpload') : $imageUploadModule = false;
    	$serviceLocator->has('adapter') ? $adapter = $serviceLocator->get('adapter') : $adapter = false;
		$this->imageUpload = $imageUploadModule;
		$this->adapter = $adapter;
        return $this;
    }
}
