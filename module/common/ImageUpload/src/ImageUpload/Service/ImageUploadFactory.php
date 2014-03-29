<?php
/**
 * 建立图片上传模块ServiceManager工厂
 * @author elice
 *
 */

namespace ImageUpload\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ImageUpload\ImageUpload\ImageUpload;

class ImageUploadFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$init = $serviceLocator->get('config');
		isset($init['imageUpload/init']) ? $initArray = $init['imageUpload/init'] : $initArray = false;
    	
		return new ImageUpload($initArray);
    }
}
