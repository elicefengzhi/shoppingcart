<?php
/**
 * 数据库模块基础类
 * @author elice
 *
 */
namespace DbSql\Db;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\TableGateway;

class BaseDb extends AbstractTableGateway
{
	protected $adapter;
	protected $isContention;
	protected $tableGateway;
	
	public function init($adapter)
	{
		$this->adapter = $adapter;
		$this->tableGateway = new TableGateway($this->table,$adapter);
		$this->isContention = $this->isContention();
	}
	
	protected function isContention()
	{
		return $this->adapter->getDriver()->getConnection()->isConnected();
	}
	
	/**
	 * 开始事务
	 * @return \Zend\Db\Adapter\Driver\ConnectionInterface
	 */
	public function beginTransaction()
	{
		return $this->adapter->getDriver()->getConnection()->beginTransaction();
	}
	
	/**
	 * 提交事务
	 * @return \Zend\Db\Adapter\Driver\ConnectionInterface
	 */
	public function commit()
	{
		return $this->adapter->getDriver()->getConnection()->commit();
	}
	
	/**
	 * 事务回滚
	 * @return \Zend\Db\Adapter\Driver\ConnectionInterface
	 */
	public function rollback()
	{
		return $this->adapter->getDriver()->getConnection()->rollback();
	}
	
	/**
	 * 返回最后一个ID
	 * @return number
	 */
	public function lastInsertId()
	{
		return $this->lastInsertValue;
	}
	
	/**
	 * 获得操作数据表名
	 * @return string
	 */
	public function getTableName()
	{
		return $this->table;
	}

	/**
	 * 执行手工sql语句
	 * @param string $sql
	 * @param array $params
	 */
	public function query($sql, $params)
	{
		return $this->adapter->query($sql, $params);
	}
}