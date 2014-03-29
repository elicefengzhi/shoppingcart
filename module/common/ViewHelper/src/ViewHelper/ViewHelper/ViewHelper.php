<?php

namespace ViewHelper\ViewHelper;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class ViewHelper extends AbstractPlugin
{	
	public function __invoke($viewType)
	{
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
    	return new $class($this->imageModule);
    }
}
