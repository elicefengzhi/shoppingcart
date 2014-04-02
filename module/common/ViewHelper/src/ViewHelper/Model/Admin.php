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
    	return $sm->get('DbSql')->dispatch('ProductType')->getProductTypeByProductId($pId,array('name'),array());
    }
    
    public function getAdByProductId($pId)
    {
    	$sm = \Application\Model\StaticApplication::getServiceManager();
    	return $sm->get('DbSql')->dispatch('Ad')->getAdProductByProductId($pId,array('ad_name'),array());
    }
    
    public function getForumByProductId($pId)
    {
    	$sm = \Application\Model\StaticApplication::getServiceManager();
    	return $sm->get('DbSql')->dispatch('Forum')->getForumByProductId($pId,array('forum_name'),array());
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
}