<?php

namespace Application\Logic;

class Language
{
	private $viewHelperManager;
	private $serviceManager;
	private $locale = 'en_US';
	
	function __construct($viewHelperManager,$serviceManager)
	{
		$this->viewHelperManager = $viewHelperManager;
		$this->serviceManager = $serviceManager;
	}
	
	/**
	 * 设置当前语言
	 */
	public function setLocale()
	{
		$translateHelper = $this->viewHelperManager->get('Translate');
		$translator = $translateHelper->getTranslator();
		$translator->setLocale($this->locale);
		
		$translator = $this->serviceManager->get('translator');
		$translator->setLocale($this->locale);
	}
}