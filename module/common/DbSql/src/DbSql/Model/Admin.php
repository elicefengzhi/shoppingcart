<?php

namespace DbSql\Model;

use DbSql\Model\BaseDb;

class Admin extends BaseDb
{
    protected $table = 'admin';
    
    public function getAdminBycolumns($columns,$where)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns($columns)->where($where);
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $current[0];
    	}
    
    	return false;
    }
}
