<?php
/**
 * 建立验证模块ServiceManager工厂
 * @author elice
 *
 */

namespace Validate\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Validate\Validate\Validate;

class ValidateFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$serviceLocator->has('ImageUpload') ? $imageUploadModule = $serviceLocator->get('ImageUpload') : $imageUploadModule = false;
    	$serviceLocator->has('adapter') ? $adapter = $serviceLocator->get('adapter') : $adapter = false;

        return new Validate($imageUploadModule,$adapter);
    }
}
