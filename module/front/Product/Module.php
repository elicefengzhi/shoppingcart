<?php

namespace Product;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }
    
    public function getServiceConfig()
    {
    	return array(
    		'factories' => array(
    			'front/product/logic' => function ($sm) {
    				$productLogic = new \Product\Logic\ProductLogic();
    				$productLogic->setServiceManager($sm);
    				return $productLogic;
    			}
    		)
    	);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
