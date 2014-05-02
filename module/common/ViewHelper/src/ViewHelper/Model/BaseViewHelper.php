<?php
namespace ViewHelper\Model;

use Zend\View\Helper\AbstractHelper;

class BaseViewHelper extends AbstractHelper
{	
	protected $serviceManager;
	
	public function setServiceManager($serviceManager)
	{
		$this->serviceManager = $serviceManager;
	}
	
	protected $sourceData = array();//数据源
	
	/**
	 * string等功能函数获得数据源
	 * @param string $data
	 * @param string $key
	 * @return boolean|string
	 */
	protected function getData($data,$key)
	{
		return $data === false ? ($key === false ? (isset($this->sourceData['main']) ? $this->sourceData['main'] : false) : $this->sourceData[$key]) : $data;
	}
	
	/**
	 * 数据源赋值
	 * @param object|array $data 数据源
	 * @param string $key 数据源键名
	 */
	public function setSourceData($data,$key = false)
	{
		$key === false ? $this->sourceData['main'] = $data : $this->sourceData[$key] = $data;
	}
	
	/**
	 * 获取数据源
	 * @param string $key 数据源键名
	 * @return object|array
	 */
	public function getSourceData($key = false)
	{
	    if($key === false) {
	        return isset($this->sourceData['main']) ? $this->sourceData['main'] : false;
	    }
	    else {
	        return isset($this->sourceData[$key]) ? $this->sourceData[$key] : false;
	    }
	}
	
	/**
	 * 是否可以循环
	 * @param string $key 数据源键名
	 * @param string $data 其它数据源
	 * @return boolean
	 *
	 * 需要判断非内部数据源，参数调用：$key = false，$data = 其它数据源
	 */
	public function isLoop($key = false,$data = false)
	{
		$returnData = false;
		if(is_array($data)) {
			count($data) > 0 && $returnData = true;
		}
		else{
			if($key === false) {
				isset($this->sourceData['main']) ? $sourceData = $this->sourceData['main'] : $sourceData = false;
			}
			else {
				isset($this->sourceData[$key]) ? $sourceData = $this->sourceData[$key] : $sourceData = false;
			}
			//$key === false ? isset($this->sourceData['main']) ? $sourceData = $this->sourceData['main'] : $sourceData = false : isset($this->sourceData[$key]) ? $sourceData = $this->sourceData[$key] : $sourceData = false;
			is_array($sourceData) && count($sourceData) > 0 && $returnData = true;
		}

		return $returnData;
	}
	
	/**
	 * 数据html编码
	 * @param string $data
	 */
	public function escapeHtml($data)
	{
		$escapeHtml = new \Zend\View\Helper\EscapeHtml();
		return $escapeHtml->getEscaper()->escapeHtml($data);
	}
	
	/**
	 * 数据url编码
	 * @param string $data
	 */
	public function escapeUrl($data)
	{
		$escapeurl = new \Zend\View\Helper\EscapeUrl();
		return $escapeurl->getEscaper()->escapeUrl($data);		
	}
	
	/**
	 * 指定数据变量是否存在
	 * @param string $dataString
	 * @param object|array $data
	 * @return boolean|string
	 */
	public function isOk($dataString,$data)
	{
		$isSet = false;
		$value = '';
		$dataString === false && $value = $data;
		if(is_object($data) && isset($data->$dataString)) {
			$isSet = true;
			$value = $data->$dataString;
		}
		if(is_array($data) && isset($data[$dataString])) {
			$isSet = true;
			$value = $data[$dataString];
		}

		return $isSet === false ? false : (trim((string)$value) == '' ? false : $value);
	}
	
	/**
	 * 字符串数据输出
	 * @param string $dataString 参数名
	 * @param string $data 参数集合名
	 * @param string $key 数据源键名
	 * @param array $option 附加参数(before：字符串前添加值；after：字符串后添加值;replaceValue：为空时的替换值)
	 * @return string
	 * 
	 * 如果$dataString为false，$key为false，$data有值的情况，直接采用$data为数据源值。
	 */
	public function string($dataString,$data = false,$key = false,$option = false)
	{
		$returnData = false;
		$dataString === false && $key === false && $data!== false && $returnData = $data;
		if($returnData === false) {
			$data = $this->getData($data,$key);
			$returnData = $this->isOk($dataString,$data);
		}

		isset($option['after']) ? $after = $option['after'] : $after = '';
		isset($option['before']) ? $before = $option['before'] : $before = '';
		isset($option['isEscapeHtml']) ? $option['isEscapeHtml'] === true && $returnData = $this->escapeHtml($returnData) : $this->escapeHtml($returnData);
		isset($option['replaceValue']) ? $replaceValue = $option['replaceValue'] : $replaceValue = '';
	
		if($returnData === false) return $replaceValue;
	
		return $before.$returnData.$after;
	}
	
	/**
	 * url编码
	 * @param string $dataString 参数名
	 * @param string $data 参数集合名
	 * @param string $key 数据源键名
	 * @return string
	 */
	public function urlencode($dataString = false,$data = false,$key = false)
	{	
		if($dataString === false && $data === false) {
			return '';
		}
		else if($dataString === false && $data !== false) {
			return $this->escapeurl($data);
		}
		
		$data = $this->getData($data,$key);
		$returnData = $this->isOk($dataString,$data);

		return $returnData === false ? '' : $this->escapeurl($returnData);
	}
	
	/**
	 * 单选、复选框选中状态
	 * @param string $dataString 参数名
	 * @param string $value 选中对比值
	 * @param string $data 参数集合名
	 * @param string $key 数据源键名
	 * @param array $option 附加参数(isDefault：默认选中)
	 */
	public function check($dataString,$value,$data = false,$key = false,$option = false)
	{
		$reurnData = '';
		$data = $this->getData($data,$key);
		isset($option['isDefault']) ? $isDefault = $option['isDefault'] : $isDefault = false;

		$sourceData = $this->isOk($dataString,$data);
		$sourceData === false && $isDefault === true && count($_POST) == 0 && $reurnData = 'checked="checked"';
		$sourceData !== false && $sourceData == $value && $reurnData = 'checked="checked"';

		return $reurnData;
	}
	
	/**
	 * 下拉框选中状态
	 * @param string $dataString 参数名
	 * @param string $value 选中对比值
	 * @param string $data 参数集合名
	 * @param string $key 数据源键名
	 * @param array $option 附加参数(isDefault：默认选中)
	 */
	public function select($dataString,$value,$data = false,$key = false,$option = false)
	{
		$reurnData = '';
		$data = $this->getData($data,$key);
		isset($option['isDefault']) ? $isDefault = $option['isDefault'] : $isDefault = false;
	
		$sourceData = $this->isOk($dataString,$data);
		$sourceData === false && $isDefault === true && $reurnData = 'selected="selected"';
		$sourceData !== false && $sourceData == $value && $reurnData = 'selected="selected"';
	
		return $reurnData;
	}
	
	/**
	 * 日期格式化
	 * @param string $dataString 参数名
	 * @param string $formatterString 格式化字符串
	 * @param string $data 参数集合名
	 * @param string $key 数据源键名
	 * @return string
	 */
	public function dataFormatter($dataString = false,$formatterString,$data = false,$key = false) 
	{
		if($dataString === false) {
			$data === false ? $time = time() : $time = $data;
			return date($formatterString,$time);
		}
		
		$returnData = '';
		$data = $this->getData($data,$key);
		
		$sourceData = $this->isOk($dataString,$data);
		$sourceData !== false && $returnData = date($formatterString,$sourceData);
		
		return $returnData;
	}
	
	/**
	 * 图片显示
	 * @param string $dataString 参数名
	 * @param string $data 参数集合名
	 * @param string $key 数据源键名
	 * @param array $option 附加参数
	 * @return string|Ambigous <string, boolean>
	 * 
	 * 附加参数：
	 * width：长，height：宽，alt：图片介绍文字，before：前部添加项，after：后部添加项，replaceImg：默认图片，replaceString：默认文字，style：其它样式
	 */
	public function img($dataString,$data = false,$key = false,$option = false)
	{
		$data = $this->getData($data,$key);
		$returnData = $this->isOk($dataString,$data);
		$image = '';
		$replaceDate = '';
		$return = '';
		$tagString = array();

		isset($option['width']) && $tagString['width'] = 'width="'.$option['width'].'"';
		isset($option['height']) && $tagString['height'] = 'height="'.$option['height'].'"';
		isset($option['alt']) ? $alt = $option['alt'] : $alt = '';
		isset($option['after']) ? $after = $option['after'] : $after = '';
		isset($option['before']) ? $before = $option['before'] : $before = '';
		isset($option['replaceImg']) ? $replaceImg = $option['replaceImg'] : $replaceImg = false;
		isset($option['replaceString']) ? $replaceString = $option['replaceString'] : $replaceString = false;
		isset($option['style']) && $tagString['style'] = 'style="'.$option['style'].'"';
		isset($option['id']) && $tagString['id'] = 'id="'.$option['id'].'"';
		
		if($returnData === false && $replaceImg === false) {
			return '';
		}

		$image = BASEURL.$this->escapeHtml($returnData);
		if($replaceImg !== false && (!is_file(BASEURL.$returnData) || $returnData === false)){
			$image = $this->escapeHtml($replaceImg);
			if ($replaceString !== false) {
				$replaceDate = $replaceString;
			}
		}

		if($replaceDate != '') {
			$return['img'] = $before.'<img '.implode(' ',$tagString).' src="'.$image.'" alt="'.$alt.'" />'.$after;
			$return['replaceString'] = $replaceDate;
		}
		else {
			$return = $before.'<img '.implode(' ',$tagString).' src="'.$image.'" alt="'.$alt.'" />'.$after;
		}

		return $return;
	}
}