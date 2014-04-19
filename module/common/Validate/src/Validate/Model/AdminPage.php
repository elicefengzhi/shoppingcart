<?php

namespace Validate\Model;

use Validate\Model\BaseValidator;

class AdminPage extends BaseValidator
{
	function __construct($imageUploadModule,$adapter)
	{
		parent::__construct($imageUploadModule,$adapter);
		$this->init();
	}
	
	private function init()
	{
		$this->data['page_title'] = null;
		$this->data['page_body'] = null;
	}
	
	private function pageTitle($data)
	{
		$init = $this->dataInit(array($data),true);
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->NotEmety($data);
			if($ValidData === false) {
				$this->errorMessage['titleCheck'][] = 'タイトルを入力してください';
				return false;
			}
				
			$this->data['page_title'] = $ValidData;
		}
	}
	
	private function pageBody($data)
	{
		$init = $this->dataInit(array($data));
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->NotEmety($data,array('stringTrim'));
			if($ValidData === false) {
				$this->errorMessage['bodyCheck'][] = '内容を入力してください';
				return false;
			}
				
			$this->data['page_body'] = $ValidData;
		}
	}
	
	public function vailidAll($data)
	{
		$this->sourceData = $data;
		
		try {
			$this->pageTitle('page_title');
			$this->pageBody('page_body');
		}
		catch (\Exception $e){
			$this->isTry = false;
			$this->setLog($e->getMessage(),'DEBUG',__FILE__,__LINE__);
		}

		return $this->ReturnData();
	}
}