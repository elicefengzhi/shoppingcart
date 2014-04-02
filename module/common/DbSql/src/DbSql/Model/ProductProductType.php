<?php

namespace DbSql\Model;

use DbSql\Model\BaseDb;

class ProductProductType extends BaseDb
{
    protected $table = 'product_productType';

    public function __construct($adapter)
    {
        parent::__construct($this->table,$adapter);
    }
    
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
    
    public function edit($data,$where)
    {
    	try {
    		$return = $this->update($data,$where);
    		if($return >= 0) {
    			return true;
    		}
    	}
    	catch (\Exception $e) {
    		return false;
    	}
    }
    
    public function del($where)
    {
    	$return = $this->delete($where);
    	return $return > 0 ? true : false;
    }
    
    public function getExistsByProductId($where)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT(ppt_id)')));
    	$select->where($where);
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if($current[0]['count'] > 0) {
    		return true;
    	}
    	
    	return false;
    }
    
    public function getById($where)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns(array('ptype_id'))->where($where);
    	$resultSet = $this->tableGateway->selectWith($select);
    	$count = $resultSet->count();
    	if($count > 0) {
    		$resultSet = $resultSet->toArray();
    		return $resultSet[0]['ptype_id'];
    	}
    
    	return false;
    }
}
