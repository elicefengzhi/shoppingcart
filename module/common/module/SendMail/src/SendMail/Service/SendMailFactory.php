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
    	$initArray = false;
    	$templateArray = false;
    	
    	if(isset($init['email/init'])) {
    		if(isset($init['email/init']['send'])) {
    			$initArray = $init['email/init']['send'];
    		}
    		else {
    			throw new \Exception('email/init send not found');
    			return false;
    		}
    		
    		if(isset($init['email/init']['template'])) {
    			$templateArray = $init['email/init']['template'];
    		}
    		else {
    			throw new \Exception('email/init template not found');
    			return false;
    		}
    	}
    	else {
    		throw new \Exception('email/init not found');
    		return false;
    	}
    	
    	return new SendMail($initArray,$templateArray);
    }
}
