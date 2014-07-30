<?php

namespace DbSql\Table;

use DbSql\Db\BaseDb;

class Query extends BaseDb
{
    protected $table = 'query';
    
    public function del($where)
    {
    	$return = $this->delete($where);
    	return $return > 0 ? true : false;
    }
    
    public function getQueryAll($columns,$offset,$rowsperpage)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns($columns);
    	$select->offset($offset);
    	$select->limit($rowsperpage);
    	$select->order('create_time desc');
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $current;
    	}
    	
    	return false;
    }
    
    public function getQueryAllCount()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT(q_id)')));
        $resultSet = $this->tableGateway->selectWith($select);
        $current = $resultSet->toArray();
        if(count($current) > 0) {
        	return $current[0]['count'];
        }
        
        return 0;
    }
    
    public function getQuery($where = false,$isOne = false)
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
}
