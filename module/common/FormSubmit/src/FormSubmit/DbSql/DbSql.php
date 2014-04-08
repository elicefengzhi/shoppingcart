<?php

namespace FormSubmit\DbSql;

use Zend\Db\Sql\Sql;

Class DbSql
{
	private $dbAdapter;
	private $talbe;
	
	function __construct($dbAdapter,$talbe)
	{
		$this->dbAdapter = $dbAdapter;
		$this->talbe = $talbe;
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
	    $insert = $sql->insert($this->talbe);
	    $insert->values($data);
	    $selectString = $sql->getSqlStringForSqlObject($insert);
	    $results = $this->dbAdapter->query($selectString,$adapter::QUERY_MODE_EXECUTE);

	    return $results->count() > 0 ? $this->dbAdapter->getDriver()->getLastGeneratedValue() : false;
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
	    $update = $sql->update($this->talbe);
	    $update->set($data);
	    $update->where($where);
	    $selectString = $sql->getSqlStringForSqlObject($update);
	    $results = $this->dbAdapter->query($selectString,$adapter::QUERY_MODE_EXECUTE);
	    
	    return $results->count() >= 0 ? true : false;
	}
}