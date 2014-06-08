<?php

namespace Validate\Logic;

use Validate\Validate\BaseValidator;

class AdminProduct extends BaseValidator
{	
	public function name($data)
	{
		$init = $this->dataInit(array($data),true);
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->NotEmety($data);
			if($ValidData === false) {
				$this->errorMessage['nameCheck'][] = '商品名を入力してください';
				return false;
			}
			
			$ValidData = $this->StringLength($data,array('min'=>0,'max'=>50),false);
			if($ValidData === false) {
				$this->errorMessage['nameCheck'][] = '商品名は50文字までです';
				return false;
			}
				
			$this->data['name'] = $ValidData;
		}
	}
	
	public function originalPrice($data)
	{
		$init = $this->dataInit(array($data),true);
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->Int($data);
			if($ValidData === false) {
				$this->errorMessage['IntCheck'][] = '原価を入力してください';
				return false;
			}
	
			$this->data['original_price'] = $ValidData;
		}
	}
	
	public function price($data)
	{
		$init = $this->dataInit(array($data),true);
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->NotEmety($data);
			if($ValidData === false) {
				$this->errorMessage['priceCheck'][] = '定価を入力してください';
				return false;
			}
			
			$ValidData = $this->Int($ValidData,false);
			if($ValidData === false) {
				$this->errorMessage['priceCheck'][] = '正しい定価を入力してください';
				return false;
			}
	
			$this->data['price'] = $ValidData;
		}
	}
	
	public function stock($data)
	{
		$init = $this->dataInit(array($data),true);
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->Int($data);
			if($ValidData === false) {
				$this->errorMessage['stockCheck'][] = '正しい在庫品数を入力してください';
				return false;
			}
	
			$this->data['stock'] = $ValidData;
		}
	}
	
	public function point($data)
	{
		$init = $this->dataInit(array($data));
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->Int($data);
			if($ValidData === false) {
				$this->errorMessage['pointCheck'][] = '正しいポイントを入力してください';
				return false;
			}
	
			$this->data['point'] = $ValidData;
		}
	}
	
	public function isAdd($data)
	{
		$init = $this->dataInit(array($data));
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->Int($data,$this->filter,array('isAllowZero' => true));
			if($ValidData === false) {
				$this->setLog('param is warn from isAdd','WARN',__FILE__,__LINE__);
				$this->isDataError = true;
				return false;
			}
	
			$this->data['is_add'] = $ValidData;
		}
	}
	
	public function description($data)
	{
		$init = $this->dataInit(array($data));
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->StringLength($data,array('min' => 0,'max' => 5000),array('stringTrim','htmlEntities'));
			if($ValidData === false) {
				$this->errorMessage['nameCheck'][] = '商品説明は5000文字までです';
				return false;
			}
	
			$this->data['description'] = $ValidData;
		}
	}
	
	public function productImage()
	{
		$request = new \Zend\Http\PhpEnvironment\Request;
		$file = $request->getFiles()->toArray();
		$uploadImage = array();
		if(isset($file['image'])) {
			foreach($file['image'] as $key => $image) {
				$uploadImage['image'.$key] = $image;
				$ValidData = $this->Upload($file['image'],$key);
				if($ValidData !== false) {
					$this->uploadImage['image'.$key] = $image;
				}
			}
		}
	}
	
	public function chlidColumns($data)
	{
		$dataName = $data;
		if(isset($this->sourceData[$data])) {
			$data = $this->sourceData[$data];
			if(is_array($data) && count($data) > 0) {
				foreach($data as $key => $d) {
					$this->data[$dataName.$key] = (int)$d;
				}
			}
		}
	}
	
	public function vailidAll($data)
	{	
		return $this->validate(
			$data,
			array(
				'name' => 'name',
				'originalPrice' => 'original_price',
				'price' => 'price',
				'stock' => 'stock',
				'point' => 'point',
				'isAdd' => 'is_add',
				'description' => 'description',
				'productImage' => null,
				'chlidColumns' => 'ad',
				'chlidColumns' => 'ptypeId'
			),
			array('name' => null,'original_price' => null,'price' => null,'stock' => 0,'point' => 0,'description' => null,'is_add' => 0)
		);
	}
}