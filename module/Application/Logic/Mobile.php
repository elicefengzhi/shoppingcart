<?php

namespace Application\Logic;

use \Zend\View\Renderer\PhpRenderer;
use \Zend\View\Resolver;

class Mobile
{
	function changeViewPath($sharedEvents,$nowModuleTemplatePath)
	{
// 		$sharedEvents->attach('AdminIndex','dispatch',function($e) use($nowModuleTemplatePath) {
// 			$serviceManager = $e->getApplication()->getServiceManager();
// 			$templatePathResolver = $serviceManager->get('Zend\View\Resolver\TemplatePathStack');
// 			$templatePathResolver->setPaths(array($nowModuleTemplatePath.'/mobile')); // here is your skin name
// 		}, 100);
		$renderer = new PhpRenderer();
		
		$resolver = new Resolver\AggregateResolver();
		
		$renderer->setResolver($resolver);

		$stack = new Resolver\TemplatePathStack(array(
			'script_paths' => array(
				$nowModuleTemplatePath.'/mobile'
			)
		));
		
		$resolver->attach($stack);
	}
}