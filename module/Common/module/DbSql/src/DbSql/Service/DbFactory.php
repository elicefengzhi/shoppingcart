<?php
/**
 * 建立数据库模块ServiceManager工厂
 * @author elice
 *
 */

namespace DbSql\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DbFactory implements FactoryInterface
{
	private $adapter;
	
	function __call($className,$args)
	{
		$classNamespace = "\\DbSql\\Table\\$className";
		if(class_exists($classNamespace) === false) throw new \Exception("db class $classNamespace undefined");
		$db = new $classNamespace();
		$db->init($this->adapter);
		return $db;
	}
	
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$this->adapter = $serviceLocator->get('adapter');
        return $this;
    }
}
