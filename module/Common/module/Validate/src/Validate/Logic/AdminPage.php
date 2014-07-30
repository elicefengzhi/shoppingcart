<?php

namespace Validate\Logic;

use Validate\Validate\BaseValidator;

class AdminPage extends BaseValidator
{	
	public function pageTitle($data)
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
	
	public function pageBody($data)
	{
		$init = $this->dataInit(array($data),true);
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
		return $this->validate(
			$data,
			array('pageTitle' => 'page_title','pageBody' => 'page_body'),
			array('page_title' => null,'page_body' => null)
		);
	}
}