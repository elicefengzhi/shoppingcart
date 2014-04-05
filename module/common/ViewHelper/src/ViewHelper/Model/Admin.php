<?php

namespace ViewHelper\Model;
 
use ViewHelper\Model\BaseViewHelper;
 
class Admin extends BaseViewHelper
{
    public function __invoke()
    {
        return $this;
    }
    
    public function getProductTypeById($pId)
    {
    	$sm = \Application\Model\StaticApplication::getServiceManager();
    	return $sm->get('DbSql')->dispatch('ProductType')->getProductTypeByProductId((int)$pId,array('name'),array());
    }
    
    public function getProductTypeByOrderId($orderId)
    {
    	$sm = \Application\Model\StaticApplication::getServiceManager();
    	return $sm->get('DbSql')->dispatch('Product')->getProductTypeByOrderId((int)$orderId,array('name'),array());
    }
    
    public function getAdByProductId($pId)
    {
    	$sm = \Application\Model\StaticApplication::getServiceManager();
    	return $sm->get('DbSql')->dispatch('Ad')->getAdProductByProductId((int)$pId,array('ad_name'),array());
    }
    
    public function getForumByProductId($pId)
    {
    	$sm = \Application\Model\StaticApplication::getServiceManager();
    	return $sm->get('DbSql')->dispatch('Forum')->getForumByProductId((int)$pId,array('forum_name'),array());
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
    		    $value = '未支付';
    		    break;
    		case 1:
    			$value = '已支付';
    			break;
    		default:
    			$value = '未支付';
    	}
    	
    	return $value;
    }
}