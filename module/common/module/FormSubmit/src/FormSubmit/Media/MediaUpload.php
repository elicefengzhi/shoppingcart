<?php

namespace FormSubmit\Media;

use Zend\File\Transfer\Adapter\Http;

class MediaUpload
{
	private $uploadPath;
	private $maxSize;
	private $minSize;
	private $mimeType;
	private $uploadErrorMessage;
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
			//$sizeErrorMessage = $this->sizeErrorMessage;
			//trim((string)$sizeErrorMessage) != '' && $this->errormessage[] = sprintf($sizeErrorMessage,$maxSize);
		}
		if($returnFileSize === true) {
			if($mimeType->isValid($file['tmp_name']) === false) {
				$returnMimeType = false;
				$mimeType = $this->mimeType;
// 				$typeErrorMessage = $this->typeErrorMessage;
// 				$string = '';
// 				if(is_array($mimeType) && count($mimeType) > 0) {
// 					foreach($mimeType as $m) {
// 						$string .= str_replace('image/','',$m).'、';
// 					}
// 				}
// 				trim((string)$typeErrorMessage) != '' && $this->errormessage[] = sprintf($typeErrorMessage,rtrim($string,'、'));
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
			$mkdirs_ok = $this->mkdirs($uploadPath);
			if($mkdirs_ok === false) {
				throw new \Exception("mkdirs error");
				return false;
			}

			$newFilePath = $uploadPath.$newName;
			$http->addFilter('File\Rename',
					array('target'    => $newFilePath,
					  'overwrite' => true,
					  'source'    => $file['tmp_name'],
					));
			try {
				if ($http->receive($file['name'])) {
					$fileExists = new \Zend\Validator\File\Exists();
					if($fileExists->isValid($newFilePath) === true) {
						return $newFilePath;
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
	
	private function peelFileArray(Array $fileArray)
	{
		global $nowKey;
		$isFoundKey = false;
		foreach($fileArray as $key => $file) {
			if(is_array($file)) {
				foreach($file as $f) {
					if(array_key_exists('tmp_name',$f)) {
						$isFoundKey = true;
						break;
					}
				}
				if($isFoundKey === false) {
					$this->peelFileArray($file);
				}
				else {
					$nowKey = (int)$nowKey++;
					$this->realFileArray[$nowKey] = $file;
				}
			}
			else {
				if(array_key_exists('tmp_name',$file)) {
					$nowKey = (int)$nowKey++;
					$this->realFileArray[$nowKey] = $file;
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
		$originalKey = key($file);
		$file = $this->realFileArray[0];
		$completeFiles = array();
		$http = new Http();
		$uploadReturn = false;
		if(is_array(current($file))) {
			foreach($file as $key => $f) {
				$return = $this->fileUpload($http,$f,$path);
				if($return !== false) {
					$completeFiles[$originalKey.$key] = $this->getUploadedImagePath($return);
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
				$completeFiles[$originalKey] = $this->getUploadedImagePath($return);
			}
		}

		$uploadReturn === false && $this->events->trigger('setLog', null, array('model' => 'imageUpload','message' => $this->uploadErrorMessage,'level' => 'WARN','fileName' => __FILE__,'line' => __LINE__));
		is_array($completeFiles) && count($completeFiles) <= 0 && $completeFiles = false;

		return $completeFiles;
	}
}
