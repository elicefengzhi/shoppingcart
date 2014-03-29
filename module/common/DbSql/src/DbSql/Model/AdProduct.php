<?php

namespace DbSql\Model;

use DbSql\Model\BaseDb;

class AdProduct extends BaseDb
{
    protected $table = 'ad_product';

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
}
