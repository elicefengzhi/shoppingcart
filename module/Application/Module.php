<?php

namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Application\Model\StaticApplication;

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
    
    public function getServiceConfig()
    {
    	return array(
    		'factories' => array(
    			'Application\Service\Fsession' =>  function($sm) {
    				$Fsession  = $sm->get('Fsession');
    				return $Fsession;
    			},
    		),
    	);
    }
    
    public function onBootstrap (EventInterface $event)
    {
    	$eventManager = $event->getParam('application')->getEventManager();
    	
    	$eventManager->attach(MvcEvent::EVENT_RENDER,function(MvcEvent $event){
    		//设置页面title
    		$layoutTitle = new \Application\Model\LayoutTitle($event);
    		$layoutTitle->setLayoutTitle();
    	});
    	
    	//根据mvc调度事件，通过路由名启用后台布局页以及判断后台登陆
    	$eventManager->attach(MvcEvent::EVENT_DISPATCH, function(MvcEvent $event){
    		$is_admin = false;
    		$app = $event->getParam('application');
    		$serviceManager = $app->getServiceManager();
    		
    		//调用全局访问静态类
    		StaticApplication::setServiceManager($serviceManager);
    		
    		//获得配置
    		$init = $serviceManager->get('config');
    		//获得application模块配置
    		$applicationInit = $init['Application/init'];
    		//获得路由名
    		$routeName = $event->getRouteMatch()->getMatchedRouteName();
    		//是否是后台
    		preg_match('/'.$applicationInit['adminRoutePrefix'].'.+/',$routeName) > 0 && $is_admin = true;
    		
    		if($is_admin) {
    			//根据路由名设置布局页
    			$event->getViewModel()->setTemplate('admin/layout');
    			//判断是否登陆
    			$is_login = $serviceManager->get('Application\Service\Fsession')->hasSession($applicationInit['sessionCheckName']);
				if($is_login === false && $routeName != $applicationInit['loginRouteName']) {
					//跳转后台登陆页
			    	$url = $event->getRouter()->assemble(array(),array('name' => $applicationInit['loginRouteName']));
					$response = $event->getResponse();
					$response->getHeaders()->addHeaderLine('Location',$url);
					$response->setStatusCode(302);
					$response->sendHeaders();
				}
    		}
    	});
    	
    	//ユーザ側と管理者側の404と500テンプレートとレイアウトを設定
    	$eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function(MvcEvent $event){
    		$status = 500;
    		$is_admin = false;
//     		//获得路由名
//     		$routeName = $event->getRouteMatch()->getMatchedRouteName();
//     		//是否是后台
//     		preg_match('/admin\-.+/',$routeName) > 0 && $is_admin = true;
    		$viewModel = $event->getResult();
    		//$controller = $event->getTarget();
    		$errorType = $event->getError();
    		if(preg_match('/\/admin\/.*/',$_SERVER['REQUEST_URI'])) {
    			//管理者側404と500のテンプレートを設定
    			$viewModel->setTemplate('admin_error/index');
    			//404、500を区別する
    			if($errorType == 'error-router-no-match') {
    				$viewModel->setTemplate('admin_error/404');
    				$status = 404;
    			}
    			//管理者側404と500のレイアウトを設定
    			if($is_admin) {
    				$event->getViewModel()->setTemplate('admin/layout');
    			}
    		}
    		else {
    			//ユーザ側404と500のテンプレートを設定
    			$viewModel->setTemplate('error/index');
    			//404、500を区別する
    			if($errorType == 'error-router-no-match') {
    				$viewModel->setTemplate('error/404');
    				$status = 404;
    			}
    		}
    		
    		//设置头部信息状态
    		$response = $event->getResponse();
    		$response->setStatusCode($status);
    		$response->sendHeaders();
    	});
    }
}
