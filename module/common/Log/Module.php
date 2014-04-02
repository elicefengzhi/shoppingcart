<?php

namespace Log;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\EventManager\StaticEventManager;

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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
    	return include __DIR__ . '/config/service.config.php';
    }
    
    public function onBootstrap($e)
    {
    	//监听其它模块写日志事件
    	$events = StaticEventManager::getInstance();
    	$init = $this->getConfig();
    	$events->attach('*','setLog',function($event) use($init){
    		$params = $event->getParams();
    		isset($init['log/init']) ? $initArray = $init['log/init'] : $initArray = false;
    		$log = new \Log\Log\Log($initArray);
    		$log->write($params['model'],$params['message'], $params['level'], $params['fileName'],$params['line']);
    	});
    }
}
