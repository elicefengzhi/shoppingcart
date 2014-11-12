<?php

namespace Application\Logic\AsseticAdd;

class JsTemplateHelper
{
	private $serviceManager;
	private $config;
	private $keyWords = array('serverUrl');//内部定义替换关键字
	
	/**
	 * 获得viewHelper的serverUrl值
	 * @return string
	 */
	private function getserverUrl()
	{
		$serverUrl = $this->serviceManager->get('viewhelpermanager')->get('serverUrl');
		return $serverUrl->getScheme() . '://' . $serverUrl->getHost().'/';
	}
	
	/**
	 * 设置ServiceManager
	 * @param Zend\ServiceManager\ServiceManager $sm
	 */
	public function setServiceManager($sm)
	{
		$this->serviceManager = $sm;
	}
	
	/**
	 * 设置配置文件
	 * @param array $config
	 */
	public function setConfig($config)
	{
		$this->config = $config;
	}
	
	/**
	 * 获得替换关键字
	 * @return array
	 */
	public function getReplaceWords()
	{
		//获得外部配置的替代关键字
		$customKeyWords = isset($this->config['customKeyWords']) ? $this->config['customKeyWords'] : array();
		$keyWords = $this->keyWords;
		$newKeyWords = array();
		
		foreach($keyWords as $word) {
			//如果外部关键字没有重定义内部关键字的话，给内部关键字赋所需替换的值
			if(!array_key_exists($word,$customKeyWords)) {
				$method = 'get'.$word;
				$newKeyWords[$word] = $this->$method();
			}
		}
		
		$newKeyWords = array_merge($customKeyWords,$newKeyWords);
		return $newKeyWords;
	}
}