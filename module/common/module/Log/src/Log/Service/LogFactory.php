<?php
/**
 * 建立日志模块ServiceManager工厂
 * @author elice
 *
 */

namespace Log\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Log\Log\Log;

class LogFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$init = $serviceLocator->get('config');
    	isset($init['log/init']) ? $initArray = $init['log/init'] : $initArray = false;
        return new Log($initArray);
    }
}
