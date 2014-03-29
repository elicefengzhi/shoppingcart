<?php
/**
 * 建立邮件模块ServiceManager工厂
 * @author elice
 *
 */

namespace SendMail\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use SendMail\SendMail\SendMail;

class SendMailFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$init = $serviceLocator->get('config');
    	isset($init['email/init']) ? $initArray = $init['email/init'] : $initArray = false;
    	
        return $initArray === false ? false : new SendMail($initArray);
    }
}
