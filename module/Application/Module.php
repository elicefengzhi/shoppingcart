<?php

namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface 
{
	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ ,
				),
			),
		);
	}
	
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getViewHelperConfig()
    {
    	//控制器没有像试图层传试图助手对象，试图层直接使用自定义试图助手
    	//通过对相应模块module.config.php中定义viewHelper/dispatch来分发试图助手
    	return array(
    		'factories' => array(
    			'viewHelper' => function($sm) {
    				$config = $sm->getServiceLocator()->get('config');
    				isset($config['viewHelper/dispatch']) ? $viewHelper = '\ViewHelper\Logic\\'.$config['viewHelper/dispatch'] : $viewHelper = false;
    				return $viewHelper === false ? false : new $viewHelper();
    			},
    		),
    	);
    }
    
    public function init(ModuleManager $moduleManager)
    {   
    	//设置移动端试图层路径
    	$mobile = new \Application\Logic\Mobile();
		$mobile->changeMobileViewPath($moduleManager);
    }
    
    public function onDispatchError(MvcEvent $event){
    	//自定义错误处理
    	$response = $event->getResponse();
    	if($response->getStatusCode() == 500){
    		$errorStrategy = new \Application\Logic\ErrorStrategy();
    		$applicationConfig = $this->getConfig();
    		$errorStrategy->errorHandle($applicationConfig,$event->getParam('exception'),$event->getApplication()->getServiceManager());
    	}
    }
    
    public function onBootstrap (EventInterface $event)
    {
    	$language = new \Application\Logic\Language($event->getApplication()->getServiceManager()->get('viewHelperManager'),$event->getApplication()->getServiceManager());
    	
    	$eventManager = $event->getParam('application')->getEventManager();
    	$eventManager->getSharedManager()->attach('*', MvcEvent::EVENT_DISPATCH, array($this,'onDispatchError'), -100);
    	$eventManager->getSharedManager()->attach('*', MvcEvent::EVENT_DISPATCH_ERROR, array($this,'onDispatchError'), -100);
    	$eventManager->getSharedManager()->attach('*', MvcEvent::EVENT_RENDER_ERROR, array($this,'onDispatchError'), - 100);
    }
}
