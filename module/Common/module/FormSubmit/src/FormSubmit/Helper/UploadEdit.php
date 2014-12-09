<?php

namespace FormSubmit\Helper;

use FormSubmit\Helper\BaseHelper;

class UploadEdit extends BaseHelper
{
	private $configData;
	
	/**
	 * 获得helper方法传入参数
	 * @param array $configData
	 */
	public function init(Array $configData)
	{
		$this->configData = $configData;
	}
	
	/**
	 * 删除物理文件
	 * @param string $file
	 */
	private function unlinkFile($file)
	{
		if(is_file($file)) {
			@unlink($file);
		}
	}
	
	/**
	 * 媒体上传更新时'必填'的判断
	 * @example
	 * $formSubmit->mediaUpload()->helper('UploadAfter','UploadEdit',array('upload' => array('isRequired' => true,'path' => 'test.jpg','errorMessage' => '错误信息')))<br/>
	 * 第三个参数解释：
	 * upload为字段名，isRequired为是否必填，path为从数据库中读取出来的值，errorMessage为错误信息
	 * 
	 * @return boolean
	 */
	public function action()
	{	
		//从formSubmit对象中获得上传完成后的文件地址
		$newUploadData = $this->formSubmit->getUploadedPath();
		//获得配置信息
		$config = $this->formSubmit->getInitConfig();
		//获得上传根目录
		$uploadPath = $config['media']['uploadPath'];
		
		foreach($this->configData as $key => $file) {
			//旧文件的物理地址为配置的uploadPath值加上第三个参数path的值
			$oldFile = $uploadPath.$file['path'];
			//如果是必填项并且上传文件地址中该字段存在(说明新文件已经上传成功)，则物理删除旧文件；上传地址没有并且数据库中不存在值，则把错误信息写入formSubmit对象的validateErrorMessage中
			//如果不是必填但是上传文件地址中该字段存在，则物理删除旧文件
			if((boolean)$file['isRequired']) {
				if(isset($newUploadData[$key])) {
					$this->unlinkFile($oldFile);
				}
				else if(empty($file['path'])) {
					$errorMessage = $this->formSubmit->getValidateErrorMessage();
					$errorMessage[$key]['isEmpty'] = $file['errorMessage'];
					$this->formSubmit->validateErrorMessage($errorMessage);
					$this->formSubmit->setIsVal(false);
				}
			}
			else if(isset($newUploadData[$key])){
				$this->unlinkFile($oldFile);
			}
		}
	}
}