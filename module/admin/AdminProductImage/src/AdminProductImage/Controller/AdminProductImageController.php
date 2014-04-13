<?php

namespace AdminProductImage\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class AdminProductImageController extends AbstractActionController
{
    public function indexAction()
    {
    	$pId = $this->params('pId',false);
    	$imageList = $this->serviceLocator->get('DbSql')->dispatch('ProductImage')->getImageByProductId(array('image_path'),array('product_id' => (int)$pId));
    	$viewHelper = $this->viewHelper('Admin');
    	$viewHelper->setSourceData($imageList);
        return array('viewHelper' => $viewHelper);
    }

    public function deleteAction()
    {
    	$imageId = $this->params('imageId',false);
    	$return = 'false';
    	if($imageId !== false) {
    		$imagePath = $this->serviceLocator->get('DbSql')->dispatch('ProductImage')->getImageByProductId(array('image_path'),array('image_id' => (int)$imageId),true);
    		$return = $this->serviceLocator->get('DbSql')->dispatch('ProductImage')->del(array('image_id' => (int)$imageId));
    		$return === true && isset($imagePath['image_path']) && is_file(BASEPATH.$imagePath['image_path']) && @unlink(BASEPATH.$imagePath['image_path']) && $return = 'true';
    	}
    	
    	echo $return;
		exit;
    }
}
