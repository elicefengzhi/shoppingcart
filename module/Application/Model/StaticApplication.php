<?php

namespace Application\Model;

class StaticApplication
{
	private static $sm;
	
	public static function setServiceManager($sm)
	{
		self::$sm = $sm;
	}
	
	public static function getServiceManager()
	{
		return self::$sm;
	}
}