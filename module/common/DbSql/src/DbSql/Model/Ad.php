<?php

namespace DbSql\Model;

use DbSql\Model\BaseDb;

class Ad extends BaseDb
{
    protected $table = 'advertisement';
    
    public function getAdAll()
    {
    	$select = $this->tableGateway->getSql()->select();
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $current;
    	}
    	
    	return false;
    }
    
    public function getAdProductByProductId($productId,$masterColumns,$joinColumns)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns($masterColumns);
    	$select->join(array('adp' => 'ad_product'),'adp.ad_id = advertisement.ad_id',$joinColumns);
    	$select->where(array('adp.product_id' => $productId));
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $current;
    	}
    
    	return false;
    }
}
