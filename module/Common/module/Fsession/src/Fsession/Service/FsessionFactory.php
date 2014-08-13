<?php
/**
 * 建立session模块ServiceManager工厂
 * @author elice
 *
 */

namespace Fsession\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Fsession\Fsession\Fsession;

class FsessionFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$init = $serviceLocator->get('config');
    	isset($init['Fsession/init']) ? $initArray = $init['Fsession/init'] : $initArray = false;
    	$dbAdapter = $serviceLocator->get('Fsession/adapter');
        return new Fsession($initArray,$dbAdapter);
    }
}
