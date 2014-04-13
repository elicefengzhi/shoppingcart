<?php

namespace Validate\Model;

use Validate\Model\BaseValidator;

class AdminProduct extends BaseValidator
{
	function __construct($imageUploadModule,$adapter)
	{
		parent::__construct($imageUploadModule,$adapter);
		$this->init();
	}
	
	private function init()
	{
		$this->data['name'] = null;
		$this->data['original_price'] = null;
		$this->data['price'] = null;
		$this->data['stock'] = 0;
		$this->data['point'] = 0;
		$this->data['description'] = null;
		$this->data['is_add'] = 0;
	}
	
	private function name($data)
	{
		$init = $this->dataInit(array($data),true);
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->NotEmety($data);
			if($ValidData === false) {
				$this->errorMessage['nameCheck'][] = '商品名为必须';
				return false;
			}
			
			$ValidData = $this->StringLength($data,array('min'=>0,'max'=>50),false);
			if($ValidData === false) {
				$this->errorMessage['nameCheck'][] = '商品名必须在50字以内';
				return false;
			}
				
			$this->data['name'] = $ValidData;
		}
	}
	
	private function originalPrice($data)
	{
		$init = $this->dataInit(array($data));
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->Int($data);
			if($ValidData === false) {
				$this->errorMessage['IntCheck'][] = '原价请填写正常数字';
				return false;
			}
	
			$this->data['original_price'] = $ValidData;
		}
	}
	
	private function price($data)
	{
		$init = $this->dataInit(array($data),true);
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->NotEmety($data);
			if($ValidData === false) {
				$this->errorMessage['priceCheck'][] = '现价为必须';
				return false;
			}
			
			$ValidData = $this->Int($ValidData,false);
			if($ValidData === false) {
				$this->errorMessage['priceCheck'][] = '现价请填写正常数字';
				return false;
			}
	
			$this->data['price'] = $ValidData;
		}
	}
	
	private function stock($data)
	{
		$init = $this->dataInit(array($data));
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->Int($data);
			if($ValidData === false) {
				$this->errorMessage['stockCheck'][] = '库存请填写正常数字';
				return false;
			}
	
			$this->data['stock'] = $ValidData;
		}
	}
	
	private function point($data)
	{
		$init = $this->dataInit(array($data));
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->Int($data);
			if($ValidData === false) {
				$this->errorMessage['pointCheck'][] = '点数请填写正常数字';
				return false;
			}
	
			$this->data['point'] = $ValidData;
		}
	}
	
	private function isAdd($data)
	{
		$init = $this->dataInit(array($data));
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->Int($data,$this->filter,true);
			if($ValidData === false) {
				$this->setLog('param is warn from isAdd','WARN',__FILE__,__LINE__);
				$this->isDataError = true;
				return false;
			}
	
			$this->data['is_add'] = $ValidData;
		}
	}
	
	private function description($data)
	{
		$init = $this->dataInit(array($data));
		if($init === true) {
			$data = $this->sourceData[$data];
			$ValidData = $this->StringLength($data,array('min' => 0,'max' => 5000),array('stringTrim','htmlEntities'));
			if($ValidData === false) {
				$this->errorMessage['nameCheck'][] = '商品说明必须在5000字以内';
				return false;
			}
	
			$this->data['description'] = $ValidData;
		}
	}
	
	private function productImage()
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
	
	private function chlidColumns($data)
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
		$this->sourceData = $data;
		
		try {
			$this->name('name');
			$this->originalPrice('original_price');
			$this->price('price');
			$this->stock('stock');
			$this->point('point');
			$this->isAdd('is_add');
			$this->description('description');
			$this->productImage();
			$this->chlidColumns('ad');
			$this->chlidColumns('ptypeId');

		}
		catch (\Exception $e){
			$this->isTry = false;
			$this->setLog($e->getMessage(),'DEBUG',__FILE__,__LINE__);
		}

		return $this->ReturnData();
	}
}