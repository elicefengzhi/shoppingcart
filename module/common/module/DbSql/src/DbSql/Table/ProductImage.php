<?php

namespace DbSql\Table;

use DbSql\Db\BaseDb;

class ProductImage extends BaseDb
{
    protected $table = 'image_product';
    
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
    
    public function getImageByProductId($columns = false,$where,$isOne = false,$limit = false)
    {
        if(is_array($columns)) {
    		$select = $this->tableGateway->getSql()->select();
    		$select->columns($columns)->where($where);
			$limit !== false && $select->limit($limit);
    		$resultSet = $this->tableGateway->selectWith($select);
    	}
    	else {
    		$resultSet = $this->tableGateway->select($where);
    	}
    	$count = $resultSet->count();
    	if($count > 0) {
    		$return = $resultSet->toArray();
    		return $isOne === false ? $return : $return[0];
    	}
    	else {
    		return false;
    	}   	
    }
}
