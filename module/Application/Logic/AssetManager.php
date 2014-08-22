<?php

namespace Application\Logic;

class AssetManager
{
	function __construct()
	{
		$this->changeDefaultSetting();
	}
	
	/**
	 * 改变默认资源路径时需要更改的默认设置
	 */
	private function changeDefaultSetting()
	{
		$GLOBALS['UPLOADPATH'] = BASEPATH.'theme/default/upload/';
	}
}