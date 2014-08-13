<?php

namespace DbSql\Table;

use DbSql\Db\BaseDb;

class Order extends BaseDb
{
    protected $table = 'order';
    
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
    
    public function getOrderAllCount()
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT(order.order_id)')));
    	$select->join('user','user.user_id = order.user_id',array());
    	$select->where(array('delete_flg' => 0));
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $current[0]['count'];
    	}
    	
    	return 0;
    	
    }
    
    public function getOrderAll($offset,$rowsperpage)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns(array('order_id','total','point','status','creat_time','update_time'));
    	$select->join('user','user.user_id = order.user_id',array('user_id','user_name'),$select::JOIN_LEFT);
    	$select->order('order.update_time desc');
    	$select->where(array('delete_flg' => 0));
    	$select->offset($offset);
    	$select->limit($rowsperpage);
    	$resultSet = $this->tableGateway->selectWith($select);
    	$current = $resultSet->toArray();
    	if(count($current) > 0) {
    		return $current;
    	}
    	
    	return false;
    }
    
    public function getPaginator($currentPageNumber,$itemCountPerPage)
    {
    	$select = $this->tableGateway->getSql()->select();
    	$select->columns(array('order_id','total','point','status','creat_time','update_time'));
    	$select->join('user','user.user_id = order.user_id',array('user_id','user_name'),$select::JOIN_LEFT);
    	$select->order('order.update_time desc');
    	$select->where(array('delete_flg' => 0));
    
    	return $this->paginator($select,$currentPageNumber,$itemCountPerPage);
    }
}
