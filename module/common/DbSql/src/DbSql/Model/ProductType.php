<?php

namespace DbSql\Model;

use DbSql\Model\BaseDb;

class ProductType extends BaseDb
{
    protected $table = 'product_type';

    public function __construct($adapter)
    {
        parent::__construct($this->table,$adapter);
    }
    
    public function del($where)
    {
    	$return = $this->delete($where);
    	return $return > 0 ? true : false;
    }
    
    public function getAllCount()
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT(ptype_id)')));
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $current[0]['count'];
    	}
    	 
    	return 0;
    }
    
    public function getTypeAll($offset = false,$rowsperpage = false)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->join(array('pt' => 'product_type'),'product_type.parent_id = pt.ptype_id',array('parent_name' => 'name'),$select::JOIN_LEFT);
    	if($offset !== false && $rowsperpage !== false) {
    		$select->offset($offset);
    		$select->limit($rowsperpage);
    	}
    	$select->order('product_type.parent_id asc');
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $current;
    	}
    	
    	return false;
    }
    
    public function getProductTypeByProductId($productId,$masterColumns,$joinColumns)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns($masterColumns);
    	$select->join(array('ppt' => 'product_productType'),'ppt.ptype_id = product_type.ptype_id',$joinColumns);
    	$select->where(array('ppt.product_id' => $productId));
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $current;
    	}
    	
    	return false;
    }
    
    public function getType($where = false,$isOne = false)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->where($where);
    	$resultSet = $this->tableGateway->selectWith($select);
		$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $isOne === false ? $current : $current[0];
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
