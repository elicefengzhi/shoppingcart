<?php

namespace Application\Controller\Admin;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class BaseController extends AbstractActionController
{
	protected function attachDefaultListeners()
	{
		parent::attachDefaultListeners();
		$events = $this->getEventManager();
		$events->attach(MvcEvent::EVENT_DISPATCH, array($this,'onAdminPreDispatch'), 100);
	}
	
	public function onAdminPreDispatch($event)
	{
		$serviceManager = $event->getParam('application')->getServiceManager();
		$isLogin = $serviceManager->get('Fsession')->hasSession('adminId');
		//判断是否登陆
		$routeName = $event->getRouteMatch()->getMatchedRouteName();
		if($isLogin === false && $routeName != 'admin-index/login') {
			//跳转后台登陆页
	    	$url = $event->getRouter()->assemble(array(),array('name' => 'admin-index/login'));
			$response = $event->getResponse();
			$response->getHeaders()->addHeaderLine('Location',$url);
			$response->setStatusCode(302);
			$response->sendHeaders();
		}
		else if($routeName == 'admin-index/login'){
			//设置后台登陆布局页
			$event->getViewModel()->setTemplate('admin/login/layout');
		}
		else {
			//设置后台布局页
			$event->getViewModel()->setTemplate('admin/layout');
		}
		//设置页面title
		$this->setLayoutTitle($event);
	}
	
	public function setLayoutTitle($event)
	{
		$layoutConfig = include APPLICATIONPATH."config/admin/layout.config.php";
		$event = $this->event;
		$matches = $event->getRouteMatch();
		$controller = $matches->getParam('controller');
		$action = $matches->getParam('action');
		$title = '';
		isset($layoutConfig[$controller]) && isset($layoutConfig[$controller][$action]) && $title = $layoutConfig[$controller][$action]['title'];
	
		$viewHelperManager = $event->getApplication()->getServiceManager()->get('viewHelperManager');
		$headTitleHelper = $viewHelperManager->get('headTitle');
		$headTitleHelper->append($title);
	}
}