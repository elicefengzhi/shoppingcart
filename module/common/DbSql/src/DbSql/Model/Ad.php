<?php

namespace DbSql\Model;

use DbSql\Model\BaseDb;

class Ad extends BaseDb
{
    protected $table = 'advertisement';

    public function __construct($adapter)
    {
        parent::__construct($this->table,$adapter);
    }
    
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
}
