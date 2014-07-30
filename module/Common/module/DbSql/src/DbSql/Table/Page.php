<?php

namespace DbSql\Table;

use DbSql\Db\BaseDb;

class Page extends BaseDb
{
    protected $table = 'page';
    
    public function add($data)
    {
        $data['create_time'] = time();
        $data['update_time'] = $data['create_time'];
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
    
    public function getPageAllCount()
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT(page_id)')));
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $current[0]['count'];
    	}
    
    	return 0;
    }
    
    public function getPageAll($offset,$rowsperpage)
    {
        $select = $this->tableGateway->getSql()->select();
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
    
    public function getPage($where = false,$isOne = false)
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
