<?php

namespace Validate\Validate;

class Validate
{
	private $imageModule;
	private $adapter;
	
	function __construct($imageModule,$adapter)
	{
		$this->imageModule = $imageModule;
		$this->adapter = $adapter;
	}
	
    /**
     * 验证类分发
     * @param string $type
     * @return boolean|object
     */
    public function dispatch($type)
    {
    	if(empty($type)) return false;
    	 
    	$class = 'Validate\Model\\'.$type;
    	return new $class($this->imageModule,$this->adapter);
    }
}
