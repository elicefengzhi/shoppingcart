<?php

namespace ViewHelper\ViewHelper;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class ViewHelper extends AbstractPlugin
{	
	private $serviceManager;//数据库适配器
	
	function init($serviceManager)
	{
		$this->serviceManager = $serviceManager;
	}
	
	public function __invoke($viewType,$serviceManager)
	{
		$this->serviceManager = $serviceManager;
		return $this->dispatch($viewType);
	}
	
    /**
     * 试图助手类分发
     * @param string $viewType
     * @return boolean|object
     */
    public function dispatch($viewType)
    {
    	if(empty($viewType)) return false;

    	$class = 'ViewHelper\Model\\'.$viewType;
    	$viewHelper = new $class();
    	$viewHelper->setServiceManager($this->serviceManager);
    	return $viewHelper;
    }
}
