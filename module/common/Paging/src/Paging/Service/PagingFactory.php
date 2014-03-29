<?php
/**
 * 建立分页模块ServiceManager工厂
 * @author elice
 *
 */

namespace Paging\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Paging\Paging\Paging;

class PagingFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Paging();
    }
}
