<?php

namespace Validate\Model;

use Validate\Model\BaseValidator;

class AdminProductType extends BaseValidator
{
	function __construct($imageUploadModule,$adapter)
	{
		parent::__construct($imageUploadModule,$adapter);
		$this->init();
	}
	
	private function init()
	{
		$this->data['name'] = null;
		$this->data['parent_id'] = 0;
	}
	
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
		$this->sourceData = $data;
		
		try {
			$this->name('name');
			$this->parentId('parent_id');
		}
		catch (\Exception $e){
			$this->isTry = false;
			$this->setLog($e->getMessage(),'DEBUG',__FILE__,__LINE__);
		}

		return $this->ReturnData();
	}
}