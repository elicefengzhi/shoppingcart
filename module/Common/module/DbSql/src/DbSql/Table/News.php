<?php

namespace DbSql\Table;

use DbSql\Db\BaseDb;

class News extends BaseDb
{
	protected $table = 'news';
	
	public function add($data)
	{
		return $this->addData($data);
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
	
	public function getPaginator($currentPageNumber,$itemCountPerPage)
	{
		$select = $this->tableGateway->getSql()->select();
		$select->where(array('delete_flg' => 0));
		$select->order('update_time desc');
		
		return $this->paginator($select,$currentPageNumber,$itemCountPerPage);
	}
	
	public function getNewsAllCount()
	{
		$select = $this->tableGateway->getSql()->select();
		$select->where(array('delete_flg' => 0));
		$select->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT(news_id)')));
		$resultSet = $this->tableGateway->selectWith($select);
		$current = $resultSet->toArray();
		if(count($current) > 0) {
			return $current[0]['count'];
		}
	
		return 0;
	}
	
	public function getNewsAll($offset,$rowsperpage)
	{
		$select = $this->tableGateway->getSql()->select();
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
	
	public function getNews($where = false,$isOne = false,$columns = false,$limit = false)
	{
		$select = $this->tableGateway->getSql()->select();
		$columns !== false && $select->columns($columns);
		$select->where(array('delete_flg' => 0));
		$select->where($where);
		$limit === true && $select->limit($limit);
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->current();
// 		$current = $resultSet->toArray();
// 		if(count($current) > 0) {
// 			return $isOne === false ? $current : $current[0];
// 		}
	
// 		return false;
	}
}