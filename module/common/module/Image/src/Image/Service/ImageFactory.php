<?php
/**
 * 建立Image模块ServiceManager工厂
 * @author elice
 *
 */

namespace Image\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ImageFactory implements FactoryInterface
{
	private $init;
	private $imagine;
	private $validate;
	
	function __call($className,$args)
	{
		$classNamespace = "\\Image\\Image\\$className";
		if(class_exists($classNamespace) === false) throw new \Exception("image class $classNamespace undefined"); 
		return new $classNamespace($this->init,$this->imagine,$this->validate);
	}
	
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$init = $serviceLocator->get('config');
    	isset($init['Image/init']) ? $initArray = $init['Image/init'] : $initArray = false;
    	
    	$extension = $init['Image/init']['extensionOrder'];
    	if($extension == 'imagick') {
    		extension_loaded('imagick') ? $imagine = new \Imagine\Imagick\Imagine() : $imagine = new \Imagine\Gd\Imagine();
    	}
    	else if($extension == 'gmagick') {
    		extension_loaded('gmagick') ? $imagine = new \Imagine\Gmagick\Imagine() : $imagine = new \Imagine\Gd\Imagine();
    	}
		else {
			$imagine = new \Imagine\Gd\Imagine();
		}
		
		$this->init = $initArray;
		$this->imagine = $imagine;
		$this->validate = $serviceLocator->get('Image\Validate');
    	
        return $this;
    }
}
