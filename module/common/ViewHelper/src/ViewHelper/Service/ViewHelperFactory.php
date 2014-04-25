<?php
/**
 * 建立视图助手模块ServiceManager工厂
 * @author elice
 *
 */

namespace ViewHelper\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ViewHelper\ViewHelper\ViewHelper;

class ViewHelperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $viewHelper = new ViewHelper();
        $viewHelper->init($serviceLocator);
        return $viewHelper;
    }
}
