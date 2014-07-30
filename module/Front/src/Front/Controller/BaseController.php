<?php

namespace Front\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class BaseController extends AbstractActionController
{
	protected function attachDefaultListeners()
	{
		parent::attachDefaultListeners();
		$events = $this->getEventManager();
		$events->attach(MvcEvent::EVENT_DISPATCH, array($this,'onFrontPreDispatch'), 100);
	}
	
	public function onFrontPreDispatch($event)
	{
		//设置页面title
		$this->setLayoutTitle($event);
	}
	
	public function setLayoutTitle($event)
	{
		
	}
}