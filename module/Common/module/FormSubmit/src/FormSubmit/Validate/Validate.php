<?php

namespace FormSubmit\Validate;

use Zend\Db\Sql\Ddl\Column\Varchar;
class Validate
{
	private $requestType;
	private $adapter;
	
	/**
	 * 设置是insert还是update
	 * @param unknown $requestType
	 */
	public function setRequestType($requestType)
	{
		$this->requestType = $requestType;
	}
	
	/**
	 * 设置数据库适配器
	 * @param object $adapter
	 */
	public function setAdapter($adapter)
	{
		$this->adapter = $adapter;
	}
	
	/**
	 * 检查数据是否存在
	 * @param string $table 数据表名
	 * @param array $existsArray
	 * @return boolean
	 */
	public function existsValidate($table,$where,$existsArray,$existsWhere)
	{
		$type = $this->requestType;
		$adapter = $this->adapter;
		$exists = false;

		foreach($existsArray as $existsDataKey => $existsDataValue) {
			if($this->requestType == 'insert') {
				//如果是Zend\Db\Sql\Select对象直接赋值
				if($existsWhere instanceof \Zend\Db\Sql\Select) {
					$recordExistsOption = $existsWhere;
				}
				//如果是数组，转换成Zend\Db\Sql\Select对象
				else {
					$select = new \FormSubmit\DbSql\DbSql($adapter,$table);
					$recordExistsOption = $select->getExistsSelectObject($existsDataKey,$where,$existsDataValue,$existsWhere);
				}
				$validator = new \Zend\Validator\Db\RecordExists($recordExistsOption);
				$validator->setAdapter($adapter);
				if($validator->isValid($existsDataValue) === false) return false;
			}
			else {
				/**
					说明：
					假设当前的字段为news_id(主键),news_title(不可重复字段),news_body。当前表为News
					则对应为where(array('news_id' => 4))->existsFields(array('news_title'))。(假设当前news_id为4)
					程序执行sql语句为：
					SELECT News.news_id AS news_id FROM News WHERE news_title = '输入的值' 
					通过查询出来的news_id与[where(array('news_id' => 4))]中提供的值对比，如果一致则验证通过返回false，否则返回true
				 */
				$select = new \FormSubmit\DbSql\DbSql($adapter,$table);
				$current = $select->getWhereByField(array_keys($where),$existsArray,$existsWhere);
				if(count($current) > 1){
					foreach($current as $key => $data) {
						if($where[$key] != $data) return true;
					}
				}
				else if(count($current) > 0) {
					if($where[key($current)] != current($current)) return true;
				}

				return false;
			}
		}
	
		return true;
	}	
}