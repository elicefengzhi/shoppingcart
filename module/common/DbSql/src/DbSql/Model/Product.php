<?php

namespace DbSql\Model;

use DbSql\Model\BaseDb;

class Product extends BaseDb
{
    protected $table = 'product';

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
    
    public function getProductAllCount()
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT(product.product_id)')));
    	$select->join('product_type','product.ptype_id = product_type.ptype_id',array(),$select::JOIN_LEFT);
    	$select->order('product.update_time desc');
    	$select->where(array('delete_flg' => 0));
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $current[0]['count'];
    	}
    	 
    	return 0;
    }
    
    public function getProductAll($offset,$rowsperpage)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns(array('product_id','name','original_price','price','stock','is_add','creat_time','update_time'));
    	$select->join('product_type','product.ptype_id = product_type.ptype_id',array('type_name' => 'name'),$select::JOIN_LEFT);
    	$select->order('product.update_time desc');
    	$select->where(array('delete_flg' => 0));
    	$select->offset($offset);
    	$select->limit($rowsperpage);
    	$select->order('update_time desc');
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $current;
    	}
    	
    	return false;
    }
    
    public function getProductById($where)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->where($where);
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $current[0];
    	}
    	 
    	return false;
    }
    
    public function getById($where)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns(array('product_id'))->where($where);
    	$resultSet = $this->tableGateway->selectWith($select);
    	$count = $resultSet->count();
    	if($count > 0) {
    		$resultSet = $resultSet->toArray();
    		return $resultSet[0]['product_id'];
    	}
    
    	return false;
    }
}
