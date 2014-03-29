<?php
/**
 * 建立数据库模块ServiceManager工厂
 * @author elice
 *
 */

namespace DbSql\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DbSql\Db\Db;

class DbFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$adapter = $serviceLocator->get('adapter');
        return new Db($adapter);
    }
}
