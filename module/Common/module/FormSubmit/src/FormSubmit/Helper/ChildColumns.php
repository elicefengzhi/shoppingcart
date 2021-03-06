<?php

namespace FormSubmit\Helper;

use FormSubmit\Helper\BaseHelper;

class ChildColumns extends BaseHelper
{
	private $childColumnsValues;//子表字段集值
	
	/**
	 * 获取子字段
	 * @param string $key
	 * @return boolean|array
	 */
	public function getChildColumnsValues($key)
	{
		return isset($this->childColumnsValues[$key]) ? $this->childColumnsValues[$key] : false;
	}
	
    /**
     * 例：表单内为"ad[]"形式，则$sourceKey为"ad"，本函数为其创建子表集为
	 * 	 array('ad0' => 具体值 , 'ad1' => 具体值)
     * 
     * @param string $type 数组字段的类型(input或file)
     * @param string $childColumnKey 内部使用的键名
     * @param string $sourceKey 原始的键名(标签的name)
     */
	public function init($type,$childColumnKey,$sourceKey)
	{
		//如果类型是input，源数据就是验证后requestData，否则视为文件上传来获取内容
		if($type == 'input') {
			$sourceData = $this->formSubmit->getValidatedData();
		}
		else {
			$request = new \Zend\Http\PhpEnvironment\Request;
			$sourceData = $request->getFiles()->toArray();
		}

		$dataArray = array();
		if(isset($sourceData[$sourceKey])) {
			foreach($sourceData[$sourceKey] as $dataKey => $data) {
				$dataArray[$sourceKey.$dataKey] = $data;
			}
		}

		if(count($dataArray) > 0) {
			$this->childColumnsValues[$childColumnKey] = $dataArray;
			if($type == 'input') {
				unset($sourceData[$sourceKey]);
				$this->formSubmit->validatedData($sourceData);
			}
		} 
	}
	
	public function action()
	{	
		//注册一个可被外部调用的方法
		$this->registerFunction('getChildColumnsValues');
	}
}