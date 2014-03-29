<?php

namespace Fsession\Fsession;

use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Session\Config\StandardConfig;

class Fsession
{
	private $name;
	private $remember_me_seconds;
	private $session;
	
	function __construct($init)
	{
		if($init !== false) {
			isset($init['name']) && $this->name = $init['name'];
			isset($init['remember_me_seconds']) && $this->remember_me_seconds = $init['remember_me_seconds'];
		}
		$this->init();
	}

	public function setSecond($remember_me_seconds)
	{
		$this->remember_me_seconds = (int)$remember_me_seconds;
	}
	
	/**
	 * session初始化
	 * @return boolean
	 */
	private function init()
	{
		if(trim((string)$this->name) == '' || trim((string)$this->remember_me_seconds) == '') return false;
		
		$container = new Container($this->name);
		if (!isset($container->init)) {
			//セッション基本的設定
			$config = new StandardConfig();
			$config->setOptions(array(
				'remember_me_seconds' => $this->remember_me_seconds,//セッションデータのクリア時間
			));
			$manager = new SessionManager($config);
			$manager->regenerateId(true);//セッションIDを新規に発行する
			$container->init = 1;
			Container::setDefaultManager($manager);
			$manager->getValidatorChain()->attach('session.validate', array(new \Zend\Session\Validator\HttpUserAgent(), 'isValid'));
			$manager->getValidatorChain()->attach('session.validate', array(new \Zend\Session\Validator\RemoteAddr(), 'isValid'));
		}
		
		$this->session = $container;
	}
	
	/**
	 * 获得session
	 * @param string $key
	 * @param string $value
	 */
	public function setSession($key,$value)
	{
		$this->session->$key = $value;
	}
	
	/**
	 * 设置session
	 * @param string $key
	 */
	public function getSession($key)
	{
		return $this->session->$key;
	}
	
	/**
	 * 指定session是否存在
	 * @param string $key
	 * @return boolean
	 */
	public function hasSession($key)
	{
		return $this->session->offsetExists($key);
	}
	
	/**
	 * 清空session
	 */
	public function clear()
	{
		return $this->session->getManager()->getStorage()->clear($this->name);
	}
}
