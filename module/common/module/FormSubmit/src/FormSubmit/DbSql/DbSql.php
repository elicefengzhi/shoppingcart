<?php

namespace FormSubmit\DbSql;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;

Class DbSql
{
	private $dbAdapter;
	private $table;
	
	function __construct($dbAdapter,$table)
	{
		$this->dbAdapter = $dbAdapter;
		$this->table = $table;
	}
	
	/**
	 * 数据库插入函数
	 * @param array $data 插入参数
	 * @return int|boolean 成功返回自增id，否则返回false
	 */
	public function insert($data)
	{
	    $adapter = $this->dbAdapter;
	    $sql = new Sql($adapter);
	    $insert = $sql->insert($this->table);
	    $insert->values($data);
	    $selectString = $sql->getSqlStringForSqlObject($insert);
	    $results = $this->dbAdapter->query($selectString,$adapter::QUERY_MODE_EXECUTE);

	    return $results->count() > 0 ? $this->dbAdapter->getDriver()->getLastGeneratedValue() : false;
	}
	
	/**
	 * 创建Zend\Db\Sql\Select对象用于作为数据存在验证的查询
	 * @param string $fieldName 需要验证的字段名
	 * @param string $value 需要验证的字段值
	 * @param string $where 额外的查询条件
	 * @return Zend\Db\Sql\Select
	 */
	public function getExistsSelectObject($fieldName,$where,$value,$existsWhere)
	{
		$select = new Select();
		$select = $select->from($this->table)->columns(array($fieldName))->where(array($fieldName => $value));
		if(is_array($where) || is_object($where)) {
			$select = $select->where($where);
		}
		if(is_array($existsWhere) || is_object($existsWhere)) {
			$select = $select->where($existsWhere);
		}

		return $select;
	}
	
	/**
	 * 根据给予的条件查询特定的字段
	 * @param array $fieldName
	 * @param array $where
	 * @param array $existsWhere
	 * @return array
	 */
	public function getWhereByField(Array $fieldName,$where,$existsWhere)
	{
		$select = new Select();
		$select = $select->from($this->table)->columns($fieldName);
		if(is_array($where) || is_object($where)) {
			$select = $select->where($where);
		}
		if(is_array($existsWhere) || is_object($existsWhere)) {
			$select = $select->where($existsWhere);
		}
		$sql = new \Zend\Db\Sql\Sql($this->dbAdapter);
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$current = $results->current();
		
		return $current !== false ? $current : array();
	}
	
	/**
	 * 数据库更新函数
	 * @param array $data 更新参数
	 * @param array $where 更新条件参数
	 * @return boolean 成功返回true，否则返回false
	 */
	public function update($data,$where)
	{
	    $adapter = $this->dbAdapter;
	    $sql = new Sql($adapter);
	    $update = $sql->update($this->table);
	    $update->set($data);
	    $update->where($where);
	    $selectString = $sql->getSqlStringForSqlObject($update);
	    $results = $this->dbAdapter->query($selectString,$adapter::QUERY_MODE_EXECUTE);
	    
	    return $results->count() >= 0 ? true : false;
	}
}