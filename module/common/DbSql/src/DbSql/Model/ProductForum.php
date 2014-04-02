<?php

namespace DbSql\Model;

use DbSql\Model\BaseDb;

class ProductForum extends BaseDb
{
    protected $table = 'forum_product';

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
}
