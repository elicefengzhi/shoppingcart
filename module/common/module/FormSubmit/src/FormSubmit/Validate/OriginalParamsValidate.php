<?php

namespace FormSubmit\Validate;

Class OriginalParamsValidate
{
	private $data;
	
	/**
	 * 参数是否为数组并且元素大于0
	 * @param array $param
	 * @return boolean
	 */
	private function isArray($param)
	{
		if(is_array($param) && count($param) > 0) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * 参数是非为布尔值或对象
	 * @param boolean|object $param
	 * @return boolean
	 */
	private function falseOrObject($param)
	{
		if($param === false || is_object($param)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * 验证原始参数正确性
	 * @param array $initArray
	 * @param array $requestData
	 * @param boolean|object $table
	 * @param boolean|object $validateClass
	 * @return boolean
	 */
	public function validate($initArray,$requestData,$table,$validateClass)
	{
		$isVal = true;
		$this->isArray($initArray) || $this->isArray($requestData) || $this->falseOrObject($table) || $this->falseOrObject($validateClass) || $isVal = false;
		
		return $isVal;
	}
}