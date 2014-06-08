<?php

namespace ImageUpload;

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
    	//监听其它模块图片上传事件
    	$events = StaticEventManager::getInstance();
    	$events->attach('*','uploadImage',function($event){
    		$params = $event->getParams();
    		$init = $this->getConfig();
    		isset($init['imageUpload/init']) ? $initArray = $init['imageUpload/init'] : $initArray = false;
    		$imageUpload = new \ImageUpload\ImageUpload\ImageUpload($initArray);
    		$imageUpload->upload($params['file'],$params['path']);
    	});
    }
}
