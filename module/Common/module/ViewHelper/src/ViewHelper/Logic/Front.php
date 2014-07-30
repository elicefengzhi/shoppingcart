<?php

namespace ViewHelper\Logic;
 
use ViewHelper\ViewHelper\BaseViewHelper;
 
class Front extends BaseViewHelper
{
    public function __invoke()
    {
        return $this;
    }
    
    public function getProductTypeByProductId($pId)
    {
    	return $this->serviceManager->get('DbSql')->ProductType()->getProductTypeByProductId((int)$pId,array('name'),array());
    }
    
    public function getImageByProductId($pId)
    {
    	return $this->serviceManager->get('DbSql')->ProductImage()->getImageByProductId(array('image_path'),array('product_id' => (int)$pId),true,1);
    }
}