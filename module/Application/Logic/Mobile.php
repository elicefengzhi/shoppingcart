<?php

namespace Application\Logic;

class Mobile
{
	/**
	 * 更改移动端试图层路径
	 * @param object $moduleManager
	 */
	function changeMobileViewPath($moduleManager)
	{
		$sharedEvents = $moduleManager->getEventManager()->getSharedManager();
		$sharedEvents->attach('*', 'dispatch', function($event) {
			$serviceManager = $event->getApplication()->getServiceManager();
			//获得所有模块配置
			$allConfig = $serviceManager->get('config');
			//是否开启移动端试图层
			$isChangeMobileViewPath = $allConfig['isChangeMobileViewPath'];
			if($isChangeMobileViewPath === true) {
				//是否为移动端
				$isMobile = $serviceManager->get('MobileDetect')->isMobile();
				if($isMobile) {
					//获得模板地址配置
					$templatePath = $allConfig['view_manager']['template_path_stack'];
					//获得当前请求路由参数
					$routeMatch = $event->getRouteMatch()->getParams();
					//获得当前请求模块名
					$controllerName = $routeMatch['module'];
					//获得当前请求模块原来模板地址真实路径
					$nowModuleTemplatePath = realpath($templatePath[$controllerName].'../');
				
					$templatePathResolver = $serviceManager->get('Zend\View\Resolver\TemplatePathStack');
					//设置移动端模板路径
					$templatePathResolver->setPaths(array($nowModuleTemplatePath . '/mobile'));
					
					
					$templateMap = $allConfig['view_manager']['template_map'];
					$nowModuleTemplateMap = realpath($templatePath[$controllerName]);
					foreach($templateMap as $key => $map) {
						if(strpos($map,$nowModuleTemplateMap) === 0) {
							$templateMap[$key] = str_replace($nowModuleTemplateMap,$nowModuleTemplatePath . '/mobile',$map);
						}
					}
					$templateMapResolver = $serviceManager->get('Zend\View\Resolver\TemplateMapResolver');
					$templateMapResolver->setMap($templateMap);
				}	
			}
		}, 100);
	}
}