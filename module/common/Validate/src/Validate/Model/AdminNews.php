<?php

namespace Validate\Model;

use Validate\Model\BaseValidator;

class AdminNews extends BaseValidator
{
	function __construct($imageUploadModule,$adapter)
	{
		parent::__construct($imageUploadModule,$adapter);
		$this->init();
	}
	
	private function init()
	{
		$this->data['news_title'] = null;
		$this->data['news_body'] = null;
	}
	
	private function newsTitle($data)
	{
		$init = $this->dataInit(array($data),true);
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->NotEmety($data);
			if($ValidData === false) {
				$this->errorMessage['titleCheck'][] = 'タイトルを入力してください';
				return false;
			}
				
			$this->data['news_title'] = $ValidData;
		}
	}
	
	private function newsBody($data)
	{
		$init = $this->dataInit(array($data),true);
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->NotEmety($data,array('stringTrim'));
			if($ValidData === false) {
				$this->errorMessage['bodyCheck'][] = '内容を入力してください';
				return false;
			}
				
			$this->data['news_body'] = $ValidData;
		}
	}
	
	public function vailidAll($data)
	{
		$this->sourceData = $data;
		
		try {
			$this->newsTitle('news_title');
			$this->newsBody('news_body');
		}
		catch (\Exception $e){
			$this->isTry = false;
			$this->setLog($e->getMessage(),'DEBUG',__FILE__,__LINE__);
		}

		return $this->ReturnData();
	}
}