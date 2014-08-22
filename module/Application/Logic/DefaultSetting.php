<?php

namespace Application\Logic;

class DefaultSetting
{
	function __construct()
	{
		$this->setGlobals();
	}
	
	/**
	 * 设置超级全局变量
	 */
	private function setGlobals()
	{
		$GLOBALS['UPLOADPATH'] = BASEPATH.'public/upload/';
	}
}