<?php

namespace Validate\Logic;

use Validate\Validate\BaseValidator;

class AdminNews extends BaseValidator
{	
	public function newsTitle($data)
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
	
	public function newsBody($data)
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
		return $this->validate(
			$data,
			array('newsTitle' => 'news_title','newsBody' => 'news_body'),
			array('news_title' => null,'news_body' => null)
		);
	}
}