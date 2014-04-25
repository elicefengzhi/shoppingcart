<?php

namespace DbSql\Model;

use DbSql\Model\BaseDb;

class Forum extends BaseDb
{
    protected $table = 'forum';
    
    public function getForumAll()
    {
    	$select = $this->tableGateway->getSql()->select();
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $current;
    	}
    	
    	return false;
    }
    
    public function getForumByProductId($productId,$masterColumns,$joinColumns)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns($masterColumns);
    	$select->join(array('fp' => 'forum_product'),'fp.forum_id = forum.forum_id',$joinColumns);
    	$select->where(array('fp.product_id' => $productId));
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $current;
    	}
    	 
    	return false;
    }
}
