<?php

namespace Application\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class BaseModel implements InputFilterAwareInterface
{
	private $inputArray;
	protected $translator;
	protected $inputFilter;
	protected $dbAdapter;
	
	/**
	 * 创建图片名
	 * @param string $fileName
	 * @return string
	 */
	protected function createRandFileName()
	{
		return time().floor(microtime() * 10000).rand(10, 99);
	}
	
	protected function getUploadPath(Array $file)
	{
		return 'upload/'.str_replace($GLOBALS['UPLOADPATH'],'',$file['tmp_name']);
	}
	
	public function setDbAdapter($dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
	}
	
	protected function setModel($inputFilter)
	{
		$this->setInputFilter($inputFilter);
	}
	
	protected function getModel(Array $inputArray)
	{
		$this->inputArray = $inputArray;
		return $this->getInputFilter();
	}
	
	public function setInputFilter(InputFilterInterface $inputFilter) {}
	
	public function getInputFilter()
	{
		if(!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$inputArray = $this->inputArray;
			
			if(count($inputArray > 0)) {
				foreach($inputArray as $input) {
					$inputFilter->add($input);
				}
			}
				
// 			$fileInput = new \Zend\InputFilter\FileInput('news_file');
// 			$fileInput->setRequired(true);
// 			$fileInput->getValidatorChain()
// 			->attachByName('filesize',      array('max' => 204800))
// 			->attachByName('fileMimeType',  array())
// 			->attachByName('fileimagesize', array('maxWidth' => 100, 'maxHeight' => 100));
// 			$fileInput->getFilterChain()->attachByName(
// 					'filerenameupload',
// 					array(
// 							'target'    => $GLOBALS['UPLOADPATH'].$this->createRandFileName(),
// 							'overwrite' => true,
// 							'use_upload_extension' => true
// 					)
// 			);
//			$inputFilter->add($fileInput);

			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
}