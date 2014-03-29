<?php
/**
 * 建立表单模块ServiceManager工厂
 * @author elice
 *
 */

namespace FormSubmit\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use FormSubmit\FormSubmit\FormSubmit;


class FormSubmitFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$init = $serviceLocator->get('config');
    	isset($init['FormSubmit/init']) ? $initArray = $init['FormSubmit/init'] : $initArray = false;
        return new FormSubmit($initArray,$serviceLocator);
    }
}
