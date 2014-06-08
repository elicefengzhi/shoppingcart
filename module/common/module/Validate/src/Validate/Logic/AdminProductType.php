<?php

namespace Validate\Logic;

use Validate\Validate\BaseValidator;

class AdminProductType extends BaseValidator
{	
	private function name($data)
	{
		$init = $this->dataInit(array($data),true);
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->NotEmety($data);
			if($ValidData === false) {
				$this->errorMessage['nameCheck'][] = '商品カテゴリを入力してください';
				return false;
			}
				
			$this->data['name'] = $ValidData;
		}
	}
	
	private function parentId($data)
	{
		$init = $this->dataInit(array($data));
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->NotEmety($data);
			if($ValidData === false) {
				$this->setLog('param is warn from parentId','WARN',__FILE__,__LINE__);
				$this->isDataError = true;
				return false;
			}
				
			$this->data['parent_id'] = $ValidData;
		}
	}
	
	public function vailidAll($data)
	{	
		return $this->validate(
			$data,
			array('name' => 'name','parentId' => 'parent_id'),
			array('name' => null,'parent_id' => 0)
		);
	}
}