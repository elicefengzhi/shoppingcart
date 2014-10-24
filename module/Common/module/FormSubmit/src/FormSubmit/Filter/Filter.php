<?php

namespace FormSubmit\Filter;

class Filter
{
	const STRINGTRIM = 1;
	const STRIPTAGS = 2;
	const HTMLENTITIES = 4;
	const STRIPNEWLINES = 8;
	
	/**
	 * 去空格
	 * @param string $data
	 * @return string
	 */
	private function stringTrim($data)
	{
		$StringTrim = new \Zend\Filter\StringTrim();
		$data = $StringTrim->filter($data);
		return $data;
	}
	
	/**
	 * 去标签
	 * @param string $data
	 * @return string
	 */
	private function stripTags($data)
	{
		$StripTags = new \Zend\Filter\StripTags();
		$data = $StripTags->filter($data);
		return $data;
	}
	
	/**
	 * 转义点号
	 * @param string $data
	 * @return string
	 */
	private function htmlEntities($data)
	{
		$htmlEntities = new \Zend\Filter\HtmlEntities(array('quotestyle'=>ENT_QUOTES));
		$data = $htmlEntities->filter($data);
		return $data;
	}
	
	/**
	 * 去除换行
	 * @param string $data
	 * @return stirng
	 */
	private function stripNewLines($data)
	{
		$stripNewLines = new \Zend\Filter\StripNewlines();
		$data = $stripNewLines->filter($data);
		return $data;
	}
	
	/**
	 * 过滤数据
	 * @param string $data
	 * @param int $int
	 * @return string
	 */
	private function itemFilter($data,$int)
	{
		$data = trim($data);
		switch ($int) {
			case '':
				$returnData = $data;
				break;
			case 1:
				$returnData = $this->stringTrim($data);
				break;
			case 2:
				$returnData = $this->stripTags($data);
				break;
			case 3:
				$data = $this->stringTrim($data);
				$returnData = $this->stripTags($data);
				break;
			case 4:
				$returnData = $this->htmlEntities($data);
				break;
			case 5:
				$data = $this->stringTrim($data);
				$returnData = $this->htmlEntities($data);
				break;
			case 6:
				$data = $this->stripTags($data);
				$returnData = $this->htmlEntities($data);
				break;
			case 7:
				$data = $this->stringTrim($data);
				$data = $this->stripTags($data);
				$returnData = $this->htmlEntities($data);
				break;
			case 8:
				$returnData = $this->stripNewLines($data);
				break;
			case 9:
				$data = $this->stringTrim($data);
				$returnData = $this->stripNewLines($data);
				break;
			case 10:
				$data = $this->stripTags($data);
				$returnData = $this->stripNewLines($data);
				break;
			case 11:
				$data = $this->stringTrim($data);
				$data = $this->stripTags($data);
				$returnData = $this->stripNewLines($data);
				break;
			case 12:
				$data = $this->htmlEntities($data);
				$returnData = $this->stripNewLines($data);
				break;
			case 13:
				$data = $this->stringTrim($data);
				$data = $this->htmlEntities($data);
				$returnData = $this->stripNewLines($data);
				break;
			case 14:
				$data = $this->stripTags($data);
				$data = $this->htmlEntities($data);
				$returnData = $this->stripNewLines($data);
				break;
			case 15:
			default:
				$data = $this->stringTrim($data);
				$data = $this->stripTags($data);
				$data = $this->htmlEntities($data);
				$returnData = $this->stripNewLines($data);
				break;
		}
		
		return $returnData;
	}
	
	/**
	 * 过滤数据主方法
	 * @param string $data
	 * @param boolean|array $customFilter 自定义过滤<br/>
	 * false不进行自定义过滤<br/>
	 * 
	 * @param number $int
	 * @return array
	 */
	public function filterData($data,$customFilter,$int = 15)
	{
		$int = (int)$int;
		$customFilter === false && $customFilter = array();
		
		//如果自定义过滤中包含制定字段为"null"，则在request data中注销次字段
		if(count($customFilter > 0)) {
			foreach($customFilter as $key => $custom) {
				if(is_null($custom)) {
					unset($data[$key]);
				}
			}
		}
		
		foreach($data as $key => $value) {
			//如果自定义过滤中的键包含当前键，则使用自定义的过滤项
			array_key_exists($key,$customFilter) ? $filterInt = $customFilter[$key] : $filterInt = $int;
			
			$recursiveFilter = function(&$item,$key,$filterInt) {
				$item = $this->itemFilter($item,$filterInt);
			};
			//如果当前$value仍是数组，则通过array_walk_recursive函数递归过滤
			is_array($value) ? array_walk_recursive($data[$key],$recursiveFilter,$filterInt) : $data[$key] = $this->itemFilter($value,$filterInt);
		}

		return $data;
	}
}