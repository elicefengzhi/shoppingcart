<?php

namespace Fsession\Fsession;

use Zend\Session\Container;
use Zend\Session\SessionManager;

class Fsession
{
	private $name;
	private $storage;
	private $config;
	private $database;
	private $session;
	private $dbAdapter;
	
	function __construct($init,$dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
		if($init !== false) {
			isset($init['name']) && $this->name = $init['name'];
			isset($init['storage']) && $this->storage = $init['storage'];
			isset($init['config']) && $this->config = $init['config'];
			isset($init['database']) && $this->database = $init['database'];
		}
		else {
			throw new \Exception("init params error");
		}
		$this->init();
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
	
	public function storage($storage)
	{
		$this->storage = $storage;
	}
	
	/**
	 * session初始化
	 * @return boolean
	 */
	private function init()
	{
		if(trim((string)$this->name) == '' || count($this->config) <= 0) {
			throw new \Exception("name and config params error");
			return false;
		}
		$storage = $this->storage;
	
		//session设置
		$sessionManager = new SessionManager();
		if($storage == 'database') {
			$database = $this->database;
			if(count($database) <= 0) {
				throw new \Exception("database params error");
				return false;
			}
			
			$gwOpts = new \Zend\Session\SaveHandler\DbTableGatewayOptions();
			$gwOpts->setDataColumn($database['dataColumnName']);
			$gwOpts->setIdColumn($database['idColumnName']);
			$gwOpts->setLifetimeColumn($database['lifetimeColumnName']);
			$gwOpts->setModifiedColumn($database['modifiedColumnName']);
			$gwOpts->setNameColumn($database['nameColumnName']);
			
			$saveHandler = new \Zend\Session\SaveHandler\DbTableGateway(new \Zend\Db\TableGateway\TableGateway($database['tableName'],$this->dbAdapter),$gwOpts);
			$sessionConfig = new \Zend\Session\Config\SessionConfig();
			$sessionConfig->setOptions($this->config);
			
			$sessionManager->setConfig($sessionConfig);
			$sessionManager->setSaveHandler($saveHandler);
			$sessionManager->regenerateId(true);//新sessionID
			Container::setDefaultManager($sessionManager);
			$sessionManager->start();
		}
		else {
			$config = new \Zend\Session\Config\StandardConfig();
			$config->setOptions($this->config);
			$sessionManager->setConfig($config);
			$sessionManager->regenerateId(true);//新sessionID
			Container::setDefaultManager($sessionManager);
			$sessionManager->getValidatorChain()->attach('session.validate', array(new \Zend\Session\Validator\HttpUserAgent(), 'isValid'));
			$sessionManager->getValidatorChain()->attach('session.validate', array(new \Zend\Session\Validator\RemoteAddr(), 'isValid'));
		}
		$container = new Container($this->name);
		if (!isset($container->init)) {
			$sessionManager->regenerateId(true);
			$container->init = 1;
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
