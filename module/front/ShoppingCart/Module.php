<?php

namespace ShoppingCart;

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
    			'front/shoppingCart/logic' => function ($sm) {
    				$shoppingCartLogic = new \ShoppingCart\Logic\ShoppingCartLogic();
    				$shoppingCartLogic->setServiceManager($sm);
    				return $shoppingCartLogic;
    			}
    		)
    	);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
