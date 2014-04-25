<?php

namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;
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
    	//通过对相应模块module.config.php中定义viewHelper/dispatch来分发试图助手
    	return array(
    		'factories' => array(
    			'viewHelper' => function($sm) {
    				$config = $sm->getServiceLocator()->get('config');
    				isset($config['viewHelper/dispatch']) ? $viewHelper = '\ViewHelper\Model\\'.$config['viewHelper/dispatch'] : $viewHelper = false;
    				return $viewHelper === false ? false : new $viewHelper();
    			},
    		),
    	);
    }
    
    public function onBootstrap (EventInterface $event)
    {
    	$eventManager = $event->getParam('application')->getEventManager();
    	
    	$eventManager->attach(MvcEvent::EVENT_DISPATCH, function(MvcEvent $event){
    		$app = $event->getParam('application');
    		$serviceManager = $app->getServiceManager();
    		
    		$statusCode = $event->getResponse()->getStatusCode();
    		$response = $event->getResponse();
    		$moduleName = strstr(ltrim($event->getRequest()->getRequestUri(),DIRECTORY_SEPARATOR),DIRECTORY_SEPARATOR,true);
    		$moduleName == '' && $moduleName = ltrim($event->getRequest()->getRequestUri(),DIRECTORY_SEPARATOR);

    		//404
    		if($statusCode == 404) {
    			$moduleName == 'admin' && $event->getResult()->setTemplate('admin_error/404');
    			$response->setStatusCode(404);
    			$response->sendHeaders();
    			
    		}
    		//500
    		else if($statusCode == 500) {
    			$moduleName == 'admin' && $event->getResult()->setTemplate('admin_error/index');
    			$response->setStatusCode(500);
    			$response->sendHeaders();
    		}
    	});
    }
}
