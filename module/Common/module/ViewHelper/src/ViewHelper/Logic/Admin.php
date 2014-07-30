<?php

namespace ViewHelper\Logic;
 
use ViewHelper\ViewHelper\BaseViewHelper;
 
class Admin extends BaseViewHelper
{
    public function __invoke()
    {
        return $this;
    }
    
    public function getProductTypeById($pId)
    {
    	return $this->serviceManager->get('DbSql')->ProductType()->getProductTypeByProductId((int)$pId,array('name'),array());
    }
    
    public function getProductTypeByOrderId($orderId)
    {
    	return $this->serviceManager->get('DbSql')->Product()->getProductTypeByOrderId((int)$orderId,array('name'),array('product_count'));
    }
    
    public function getAdByProductId($pId)
    {
    	return $this->serviceManager->get('DbSql')->Ad()->getAdProductByProductId((int)$pId,array('ad_name'),array());
    }
    
    public function getForumByProductId($pId)
    {
    	return $this->serviceManager->get('DbSql')->Forum()->getForumByProductId((int)$pId,array('forum_name'),array());
    }
    
    public function adCheck($adProductList,$dataString,$key)
    {
    	$sourceData = $this->isOk($dataString,$key);
    	if($sourceData !== false && is_array($adProductList)) {
    		foreach($adProductList as $data) {
    			if($data['ad_id'] == $sourceData) return 'checked="checked"';
    		}
    	}
    	
    	return '';
    }
    
    public function orderStatus($status)
    {
    	$value = '';
    	switch ($status) {
    		case 0 :
    		    $value = '未払い';
    		    break;
    		case 1:
    			$value = '支払完了';
    			break;
    		default:
    			$value = '未払い';
    	}
    	
    	return $value;
    }
}