<?php

namespace Application\Logic;

class BaseLogic
{
	protected $serviceManager;
	
	function setServiceManager($serviceManager)
	{
		$this->serviceManager = $serviceManager;
	}
}