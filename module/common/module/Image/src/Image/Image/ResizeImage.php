<?php 

namespace Image\Image;

class ResizeImage
{
	private $imagePath;//原图路径
	private $saveBasePath;//保存路径
	private $imagine;//操作类对象
	private $validate;
	private $mode = false;//模式
	private $prefix = '';//前缀
	private $option = array();//附加参数
	
	function __construct($init,$imagine,$validate)
	{
		if($init === false) throw new \Exception("resizeImage module config undefined");
		isset($init['saveBasePath']) && $this->saveBasePath = $init['saveBasePath'];
		$this->imagine = $imagine;
		$this->validate = $validate;
	}
	
	public function setSaveBasePath($saveBasePath)
	{
		$this->saveBasePath = $saveBasePath;
	}
	
	public function setImagePrefix($prefix)
	{
		$this->prefix = $prefix;
		return $this;
	}
	
	public function setMode($mode)
	{
		$this->mode = $mode;
		return $this;
	}
	
	public function setResolution($X,$Y)
	{
		$option['resolution-x'] = $X;
		$option['resolution-y'] = $Y;
		return $this;
	}
	
	public function setQuality($quality)
	{
		return $this;
	}
	
	/**
	 * 缩放图片
	 * @param string $imageFile 原图片路径
	 * @param array $size 缩放长宽
	 * @param string $version 缩放图片名前缀
	 * @param string $mode 模式(widen,heighten)
	 * widen：按长度等比缩放
	 * heighten：按高度等比缩放
	 * @return boolean
	 */
	public function Resize($imageFile,Array $size)
	{
		$isVal = $this->validate->IsVal($imageFile);
		if($isVal === false) return false;

		$image = $this->imagine->open($imageFile);
		$mode = $this->mode;
		if($mode == 'widen' || $mode === false) {
			$size = $image->getSize()->widen($size['width']);
		}
		else {
			$size = $image->getSize()->heighten($size['height']);
		}
		$baseName = new \Zend\Filter\BaseName();
		$imageBaseName = $baseName->filter($imageFile);
		$saveImageFile = $this->saveBasePath.$this->prefix.$imageBaseName;
		$image->resize($size)->save($saveImageFile,$this->option);
		
		if(is_file($saveImageFile)) {
			return true;
		}
		
		return false;
	}
}