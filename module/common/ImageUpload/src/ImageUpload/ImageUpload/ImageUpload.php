<?php

namespace ImageUpload\ImageUpload;

use Zend\File\Transfer\Adapter\Http;
use Zend\EventManager\EventManager;

class ImageUpload
{
	private $basePath;
	private $uploadPath;
	private $minWidth;
	private $maxWidth;
	private $minHeight;
	private $maxHeight;
	private $WHErrorMessage;
	private $maxSize;
	private $minSize;
	private $sizeErrorMessage;
	private $mimeType;
	private $typeErrorMessage;
	private $uploadErrorMessage;
	protected $events;
	public $errormessage = array();//错误信息
	
	function __construct($init)
	{
		if($init !== false) {
			isset($init['minSize']) && $this->minSize = $init['minSize'];
			isset($init['maxSize']) && $this->maxSize = $init['maxSize'];
			isset($init['minWidth']) && $this->minWidth = $init['minWidth'];
			isset($init['maxWidth']) && $this->maxWidth = $init['maxWidth'];
			isset($init['minHeight']) && $this->minHeight = $init['minHeight'];
			isset($init['maxHeight']) && $this->maxHeight = $init['maxHeight'];
			isset($init['mimeType']) && $this->mimeType = $init['mimeType'];
			isset($init['uploadPath']) && $this->uploadPath = $init['uploadPath'];
			isset($init['basePath']) && $this->basePath = $init['basePath'];
			isset($init['sizeErrorMessage']) && $this->sizeErrorMessage = $init['sizeErrorMessage'];
			isset($init['WHErrorMessage']) && $this->WHErrorMessage = $init['WHErrorMessage'];
			isset($init['typeErrorMessage']) && $this->typeErrorMessage = $init['typeErrorMessage'];
		}
		$this->events = new EventManager();
	}
	
	public function setSize($size)
	{
		isset($size['min']) && $this->minSize = $size['min'];
		isset($size['max']) && $this->maxSize = $size['max'];
	}
	
	public function setWidthHeight($data)
	{
		isset($data['minWidth']) && $this->minWidth = $data['minWidth'];
		isset($data['maxWidth']) && $this->maxWidth = $data['maxWidth'];
		isset($data['minHeight']) && $this->minHeight = $data['minHeight'];
		isset($data['maxHeight']) && $this->maxHeight = $data['maxHeight'];
	}
	
	public function setMimeType($mimeType)
	{
		$this->mimeType = $mimeType;
	}
	
	public function setBasePath($basePath)
	{
		$this->basePath = $basePath;
	}
	
	public function setUploadPath($uploadPath)
	{
		$this->uploadPath = $uploadPath;
	}
	
	public function setErrorMessage($errorMessage)
	{
		isset($errorMessage['sizeErrorMessage']) && $this->sizeErrorMessage = $errorMessage['sizeErrorMessage'];
		isset($errorMessage['WHErrorMessage']) && $this->WHErrorMessage = $errorMessage['WHErrorMessage'];
		isset($errorMessage['typeErrorMessage']) && $this->typeErrorMessage = $errorMessage['typeErrorMessage'];		
	}
	
	/**
	 * 递归建立目录
	 * @param string $dir
	 * @return boolean
	 */
	function mkdirs($dir)
	{
		if(!is_dir($dir))
		{
			if(!$this->mkdirs(dirname($dir))){
				return false;
			}
			if(!mkdir($dir,0777)){
				return false;
			}
			@chmod($dir,0777);
		}
		return true;
	}
	
	/**
	 * 图片重命名
	 * @param string $fileName
	 * @return string
	 */
	private function changeFileName($fileName)
	{
		$oldType = substr($fileName,strrpos($fileName,"."));
		$newName = time().floor(microtime() * 10000).rand(10, 99);
	
		return $newName.$oldType;
	}
	
	/**
	 * 验证
	 * @param array $file
	 * @return boolean
	 */
	private function validate($file)
	{
		$returnFileSize = true;
		$returnMimeType = true;
		$errorMessage = array();
		$fileSize  = new \Zend\Validator\File\Size(array('min' => $this->minSize,'max' => $this->maxSize));
		$mimeType = new \Zend\Validator\File\MimeType($this->mimeType);
		if($fileSize->isValid($file['tmp_name']) === false) {
			$returnFileSize = false;
			$maxSize = $this->maxSize;
			$sizeErrorMessage = $this->sizeErrorMessage;
			trim((string)$sizeErrorMessage) != '' && $this->errormessage[] = sprintf($sizeErrorMessage,$maxSize);
		}
		if($returnFileSize === true) {
			if($mimeType->isValid($file['tmp_name']) === false) {
				$returnMimeType = false;
				$mimeType = $this->mimeType;
				$typeErrorMessage = $this->typeErrorMessage;
				$string = '';
				if(is_array($mimeType) && count($mimeType) > 0) {
					foreach($mimeType as $m) {
						$string .= str_replace('image/','',$m).'、';
					}
				}
				trim((string)$typeErrorMessage) != '' && $this->errormessage[] = sprintf($typeErrorMessage,rtrim($string,'、'));
			}
		}

		return $returnFileSize && $returnMimeType;
	}
	
	/**
	 * 上传主函数
	 * @param object $http
	 * @param array $file
	 * @param string $path
	 * @return boolean|string
	 */
	private function fileUpload($http,$file,$path)
	{
		if($this->validate($file) === true) {
			$newName = $this->changeFileName($file['name']);
			$uploadPath = $this->uploadPath;
			$path !== false && $uploadPath .= $path.'/';
			$mkdirs_ok = $this->mkdirs($this->basePath.$uploadPath);
			if($mkdirs_ok === false) return false;
				
			$newFile = $uploadPath.$newName;
			$http->addFilter('File\Rename',
				array('target'    => $this->basePath.$newFile,
					  'overwrite' => true,
					  'source'    => $file['tmp_name'],
			));
			try {
				if ($http->receive($file['name'])) {
					$fileExists = new \Zend\Validator\File\Exists();
					if($fileExists->isValid($this->basePath.$newFile) === true) {	
						return $newFile;
					}
					else {
						$this->uploadErrorMessage = $file['name'].' receive is ok,but file exists is false';
					}
				}
				else {
					$this->uploadErrorMessage = $file['name'].' receive is warn';
				}
			}
			catch (\Exception $e){
				$this->uploadErrorMessage = $e->getMessage();
			}
		}
		
		return false;
	}
	
	/**
	 * 图片文件回滚
	 * @param array $completeFiles
	 */
	private function rollBackFiles($completeFiles)
	{
		if(count($completeFiles) > 0) {
			foreach($completeFiles as $file) {
				is_file($file) && @unlink($file);
			}
		}
	}
	
	/**
	 * 上传图片(支持多文件)
	 * @param array $file
	 * @param boolean|string $path
	 * @return boolean|string 成功返回图片地址，失败返回false
	 * 
	 * $path参数为false时图片上传到上传根目录下，为字符串时，按照字符串在上传目录生成指定目录结构并上传到此目录下
	 */
	public function upload($file,$path = false)
	{
		if(trim((string)$this->basePath) == '' || trim((string)$this->uploadPath) == '' || !is_array($this->mimeType) || count($this->mimeType) <= 0 || !is_array($file) || count($file) <= 0) return false;
		
		$completeFiles = array();
		$http = new Http();
		$uploadReturn = false;
		if(is_array(current($file))) {
			foreach($file as $key => $f) {
				$return = $this->fileUpload($http,$f,$path);
				if($return !== false) {
					$completeFiles[$key] = $return;
					$uploadReturn = true;
				} 
				else {
					$this->rollBackFiles($completeFiles);
					$uploadReturn = false;
					break;
				} 
			}
		}
		else {
			$return = $this->fileUpload($http,$file,$path);	
			if($return === false) {
				$uploadReturn = false;
			}
			else {
				$uploadReturn = true;
				$completeFiles = $return;
			}
		}

		$uploadReturn === false && $this->events->trigger('setLog', null, array('model' => 'imageUpload','message' => $this->uploadErrorMessage,'level' => 'WARN','fileName' => __FILE__,'line' => __LINE__));
		is_array($completeFiles) && count($completeFiles) <= 0 && $completeFiles = false;
		
		return $completeFiles;
	}
}
