<?php

namespace Application\Logic;

use Zend\Mvc\MvcEvent;
//use Application\Logic\Language;

class BootStrap
{	
	private $applicationConfig;
	
	/**
	 * 设置application配置变量
	 * @param array $applicationConfig
	 */
	public function setApplicationConfig($applicationConfig)
	{
		$this->applicationConfig = $applicationConfig;
	}
	
	/**
	 * application初始化
	 * @param Zend\EventManager\EventInterface $event
	 */
	public function onBootstrap($event)
	{
		//设置多语言
		//$language = new Language($event->getApplication()->getServiceManager()->get('viewHelperManager'),$event->getApplication()->getServiceManager());
		//$language->setLocale();

		//错误处理
		$eventManager = $event->getParam('application')->getEventManager();
		$eventManager->getSharedManager()->attach('*', MvcEvent::EVENT_DISPATCH, array($this,'onDispatchError'), -100);
		$eventManager->getSharedManager()->attach('*', MvcEvent::EVENT_DISPATCH_ERROR, array($this,'onDispatchError'), -100);
		$eventManager->getSharedManager()->attach('*', MvcEvent::EVENT_RENDER_ERROR, array($this,'onDispatchError'), - 100);
	}
	
	/**
	 * 错误处理
	 * @param array $applicationConfig
	 * @param object $exception
	 * @param object $sm
	 */
	public function errorHandle($applicationConfig,$exception,$sm)
	{
		if(isset($exception) !== false) {
			$className = get_class($exception);
			//获取错误相关信息
			$errorString = \Zend\Debug\Debug::dump('-----------------------------------------------------------','',false);
			$errorFileName = \Zend\Debug\Debug::dump($exception->getFile(),'File Name:',false);
			$errorFileLine = \Zend\Debug\Debug::dump($exception->getLine(),'File Line:',false);
			$server = \Zend\Debug\Debug::dump($_SERVER,'Server info:',false);
			isset($_SESSION) ? $session = \Zend\Debug\Debug::dump($_SESSION,'Session info:',false) : $session = null;
			$cookie = \Zend\Debug\Debug::dump($_COOKIE,'Cookie info:',false);
			$errorMassage = \Zend\Debug\Debug::dump($exception->getMessage(),'Error Message:',false);
			$errorTraceAsString = \Zend\Debug\Debug::dump($exception->getTraceAsString(),'Trace Error Info:',false);
			$errorString = $errorString.$errorFileName.$errorFileLine.$server.$session.$cookie.$errorMassage.$errorTraceAsString.$errorString;
	
			//错误处理
			if(isset($applicationConfig['errorStrategy'])) {
				isset($applicationConfig['errorStrategy']['type']) ? $type = $applicationConfig['errorStrategy']['type'] : $type = false;
				if($type == 'file') {
					$log = $sm->get('SetLog');
					$log->setFileName('error.log');
					$log->write('ERROR',$errorString,'WARN');
				}
				else if($type == 'email') {
					if(isset($applicationConfig['errorStrategy']['email'])) {
						$errorStrategyEmail = $applicationConfig['errorStrategy']['email'];
						if(isset($errorStrategyEmail['to'])) {
							$sm->get('SendMail')->smtpMail($errorStrategyEmail['to'],'error email',$errorString);
						}
					}
				}
			}
		}
	}
	
	/**
	 * application模块错误处理
	 * @param MvcEvent $event
	 */
	public function onDispatchError(MvcEvent $event){
		//自定义错误处理
		$response = $event->getResponse();
		if($response->getStatusCode() == 500){
			//自定义错误处理
			$applicationConfig = $this->applicationConfig;
			$this->errorHandle($applicationConfig,$event->getParam('exception'),$event->getApplication()->getServiceManager());
		}
	}
}