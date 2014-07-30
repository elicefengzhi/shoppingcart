<?php

namespace DbSql\Db;

trait DbHelper{
	/**
	 * 添加数据
	 * @param array $data 添加数据
	 * @return boolean
	 */
	protected function add($data)
	{
		$return = $this->insert($data);
		return $return > 0 ? true : false;
	}
	
	/**
	 * 删除数据
	 * @param array|string $where 删除条件
	 * @return boolean
	 */
	protected function del($where)
	{
		$return = $this->delete($where);
		return $return > 0 ? true : false;
	}
	
	/**
	 * 更新数据
	 * @param array $data 更新数据
	 * @param array|string $where 更新条件
	 * @return boolean
	 */
	protected function edit($data,$where)
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
	
	/**
	 * 查询总数
	 * @param array|string $where 查询条件<br/>
	 * @return int
	 */
	protected function getCount($where = false)
	{
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array('count' => new \Zend\Db\Sql\Expression('COUNT(*)')));
		$where !== false && $select->where($where);
		$resultSet = $this->tableGateway->selectWith($select);
		$current = $resultSet->toArray();
		if(count($current) > 0) {
			return $current[0]['count'];
		}
	
		return 0;
	}
	
	/**
	 * 单表查询
	 * @param array|string $columns 查询字段
	 * @param array|string $where 查询条件
	 * @param array $limit 范围<br/>
	 * array(limit,offset)
	 * @param boolean $isOne 是否返回一条数据<br/>
	 * 默认：false
	 * @return array|boolean
	 */
	protected function getTableByWhere($columns,$where,$limit = false,$isOne = false)
	{
		$select = $this->tableGateway->getSql()->select();
		$select->columns($columns);
		$select->where($where);
		if($limit !== false && is_array($limit)) {
			isset($limit[0]) && $select->limit($limit[0]);
			isset($limit[1]) && $select->offset($limit[1]);
		}
		$resultSet = $this->tableGateway->selectWith($select);
		$current = $resultSet->toArray();
		if(count($current) > 0) {
			return $isOne === false ? $current : $current[0];
		}
		 
		return false;
	}
}
