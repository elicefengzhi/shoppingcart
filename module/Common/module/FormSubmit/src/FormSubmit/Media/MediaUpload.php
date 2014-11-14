<?php

namespace FormSubmit\Media;

use Zend\File\Transfer\Adapter\Http;

class MediaUpload
{
	private $uploadPath;
	private $maxSize;
	private $minSize;
	private $mimeType;
	private $validateErrorMessage = array();
	private $sourceValidateErrorMessage;
	private $realFileArray = array();

	function __construct($init)
	{
		if($init !== false) {
			isset($init['minSize']) && $this->minSize = $init['minSize'];
			isset($init['maxSize']) && $this->maxSize = $init['maxSize'];
			isset($init['mimeType']) && $this->mimeType = $init['mimeType'];
			isset($init['uploadPath']) && $this->uploadPath = $init['uploadPath'];
		}
		else {
			throw new \FormSubmit\Exception\FormSubmitException('mediaUpload init params error');
		}
	}
	
	/**
	 * 设置原始验证错误提示信息
	 * @param array $sourceValidateErrorMessage
	 */
	public function setSourceValidateErrorMessage($sourceValidateErrorMessage)
	{
		$this->sourceValidateErrorMessage = $sourceValidateErrorMessage;
	}
	
	/**
	 * 获得错误信息
	 * @return multitype:
	 */
	public function getValidateErrorMessage()
	{
		return $this->validateErrorMessage;	
	}

	/**
	 * 递归建立目录
	 * @param string $dir
	 * @return boolean
	 */
	private function mkdirs($dir)
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
	private function validate($file,$inputName)
	{
		$returnFileSize = true;
		$returnMimeType = true;
		$errorMessage = array();

		$uploadFile = new \Zend\Validator\File\UploadFile();
		$minSize = new \Zend\Validator\File\Size(array('min' => $this->minSize));
		$maxSize = new \Zend\Validator\File\Size(array('max' => $this->maxSize));
		$mimeType = new \Zend\Validator\File\MimeType($this->mimeType);
		$this->sourceValidateErrorMessage === false ? $sourceErrorMessage = include __DIR__.'/../ErrorMessage/ErrorMessage.php' : $sourceErrorMessage = $this->sourceValidateErrorMessage;

		//是否是一个文件
		if($uploadFile->isValid($file) === false) {
			return null;
		}
		//最小验证
		if($minSize->isValid($file['tmp_name']) === false) {
			$returnFileSize = false;
			$this->validateErrorMessage[$inputName]['minSize'] = sprintf($sourceErrorMessage['minSizeError'],$this->minSize);
		}
		//最大验证
		if($maxSize->isValid($file['tmp_name']) === false) {
			$returnFileSize = false;
			$this->validateErrorMessage[$inputName]['maxSize'] = sprintf($sourceErrorMessage['maxSizeError'],$this->maxSize);
		}
		if($returnFileSize === true) {
			//mimeType验证
			$mimeType->setOptions(array('magicFile' => false));
			if($mimeType->isValid($file['tmp_name']) === false) {
				$returnMimeType = false;
				$mimeType = $this->mimeType;
				$this->validateErrorMessage[$inputName]['mimeTypeError'] = $sourceErrorMessage['mimeTypeError'];
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
	private function fileUpload($http,$file,$path,$inputName)
	{
		$isVal= $this->validate($file,$inputName);
		//验证通过
		if($isVal === true) {
			$newName = $this->changeFileName($file['name']);
			$uploadPath = $this->uploadPath;
			$mkdirs_ok = $this->mkdirs($uploadPath);
			if($mkdirs_ok === false) {
				throw new \FormSubmit\Exception\FormSubmitException('mkdirs error');
				return false;
			}

			$newFilePath = $uploadPath.$newName;
			$http->addFilter('File\Rename',
				array(
					'target'    => $newFilePath,
					'overwrite' => true,
					'source'    => $file['tmp_name'],
				)
			);
			try {
				if ($http->receive($file['name'])) {
					$fileExists = new \Zend\Validator\File\Exists();
					if($fileExists->isValid($newFilePath) === true) {
						return $newFilePath;
					}
					else {
						throw new \FormSubmit\Exception\FormSubmitException($file['name'].' receive is ok,but file exists is false');
					}
				}
				else {
					throw new \FormSubmit\Exception\FormSubmitException($file['name'].' receive is warn');
				}
			}
			catch (\FormSubmit\Exception\FormSubmitException $e){
				throw new \FormSubmit\Exception\FormSubmitException($e->getMessage());
			}
		}
		//空的FILE
		else if(is_null($isVal)) {
			return null;
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
	
	private function peelFileArray(Array $fileArray)
	{
		foreach($fileArray as $key => $file) {
			if(isset($file['tmp_name']) && !is_array($file['tmp_name'])) {
				$this->realFileArray[$key] = $file;
			}
			else {
				foreach($file as $childKey => $childFie) {
					$this->realFileArray[$key.$childKey] = $childFie;
				}
			}
		}
	}
	
	private function getUploadedImagePath($imagePath) {
		$imagePath = str_replace('\\','/',$imagePath);
		$replaceString = str_replace('\\','/',dirname($this->uploadPath).'/');
		return str_replace($replaceString,'',$imagePath);
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
		if(trim((string)$this->uploadPath) == '' || !is_array($this->mimeType) || count($this->mimeType) <= 0 || !is_array($file) || count($file) <= 0) return false;
		$this->peelFileArray($file);
		$file = $this->realFileArray;
		$completeFiles = array();
		$http = new Http();
		$uploadReturn = false;
		if(is_array(current($file))) {
			foreach($file as $key => $f) {
				$return = $this->fileUpload($http,$f,$path,$key);
				if(is_null($return)) {
					$uploadReturn = null;
					break;
				}
				else if($return !== false) {
					$completeFiles[$key] = $this->getUploadedImagePath($return);
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
			else if(is_null($return)) {
				$uploadReturn = null;
			}
			else {
				$uploadReturn = true;
				$completeFiles[key($file)] = $this->getUploadedImagePath($return);
			}
		}

		if(is_array($completeFiles) && count($completeFiles) <= 0) {
			$completeFiles = false;
		}
		else if(is_null($completeFiles)) {
			$completeFiles = null;
		}

		return $completeFiles;
	}
}
