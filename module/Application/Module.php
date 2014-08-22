<?php

namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Application\Logic;

class Module implements AutoloaderProviderInterface 
{
	private function assetManager($allConfig)
	{
		if(isset($allConfig['asset_manager'])) {
			$assetManager = new Logic\AssetManager();
		}
	}
	
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
    	//初始化设置
    	$defaultSetting = new Logic\DefaultSetting();
    	//设置移动端试图层路径
    	$mobile = new Logic\Mobile();
		$mobile->changeMobileViewPath($moduleManager);
    }
    
    public function onDispatchError(MvcEvent $event){
    	//自定义错误处理
    	$response = $event->getResponse();
    	if($response->getStatusCode() == 500){
    		$errorStrategy = new Logic\ErrorStrategy();
    		$applicationConfig = $this->getConfig();
    		$errorStrategy->errorHandle($applicationConfig,$event->getParam('exception'),$event->getApplication()->getServiceManager());
    	}
    }
    
    public function ss(ModuleEvent $event)
    {
    	echo '232';exit;
    }
    
    public function onBootstrap (EventInterface $event)
    {
    	//设置多语言
    	$language = new Logic\Language($event->getApplication()->getServiceManager()->get('viewHelperManager'),$event->getApplication()->getServiceManager());
    	
    	//设置资源
    	$allConfig = $event->getApplication()->getServiceManager()->get('config');
    	$this->assetManager($allConfig);
    	
    	//错误处理
    	$eventManager = $event->getParam('application')->getEventManager();
    	$eventManager->getSharedManager()->attach('*', MvcEvent::EVENT_DISPATCH, array($this,'onDispatchError'), -100);
    	$eventManager->getSharedManager()->attach('*', MvcEvent::EVENT_DISPATCH_ERROR, array($this,'onDispatchError'), -100);
    	$eventManager->getSharedManager()->attach('*', MvcEvent::EVENT_RENDER_ERROR, array($this,'onDispatchError'), - 100);
    }
}
