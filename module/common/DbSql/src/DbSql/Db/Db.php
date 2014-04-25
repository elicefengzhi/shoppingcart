<?php
/**
 * 数据库模块统一分发
 * @author elice
 *
 */
namespace DbSql\Db;

class Db
{
	private $adapter;
	
	function __construct($adapter)
	{
		$this->adapter = $adapter;
	}
	
	/**
	 * 数据操作类分发
	 * @param string $table
	 * @return boolean|object
	 */
    public function dispatch($table)
    {
    	if(empty($table)) return false;
    	
    	$class = 'DbSql\Model\\'.$table;
        $dbSql = new $class();
        $dbSql->init($this->adapter);
        return $dbSql;
    }
}
