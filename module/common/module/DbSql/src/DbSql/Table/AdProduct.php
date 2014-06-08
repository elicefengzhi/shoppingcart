<?php

namespace DbSql\Table;

use DbSql\Db\BaseDb;

class AdProduct extends BaseDb
{
    protected $table = 'ad_product';
    
    public function add($data)
    {
    	$return = $this->insert($data);
    	if($return == 1) {
    		return true;
    	}
    	else {
    		return false;
    	}
    }
    
    public function del($where)
    {
    	$return = $this->delete($where);
    	if($return > 0) {
    		return true;
    	}
    	else {
    		return false;
    	}
    }
    
    public function getExistsByProductId($where)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT(ad_product_id)')));
    	$select->where($where);
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if($current[0]['count'] > 0) {
    		return true;
    	}
    	 
    	return false;
    }
    
    public function getAdProductByWhere($columns,$where,$isOne = false)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns($columns);
    	$select->where($where);
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $isOne === false ? $current : $current[0];
    	}
    	
    	return false;
    }
}
