<?php

namespace Application\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class BaseModel implements InputFilterAwareInterface
{
	private $inputArray;//inputFilter数组参数
	private $config;//配置
	protected $inputFilter;//inputFilter对象
	protected $dbAdapter;//数据库适配器
	
	protected $fileMaxSize;//上传单文件对大容量
	protected $fileMimeType;//上传文件mimeType
	
	function __construct()
	{
		$applicationConfig = include __DIR__.'/../config/module.config.php';
		$this->config = $applicationConfig['baseModel'];
		$this->fileMaxSize = $this->config['upload']['fileMaxSize'];
		$this->fileMimeType = $this->config['upload']['fileMimeType'];
	}
	
	/**
	 * 获得数据过滤数组
	 * 
	 * 	stringTrim = 1;
	 *	stripTags = 2;
	 *	htmlEntities = 4;
	 *	stripNewLines = 8;
	 * @param int $filtersInt
	 * @return array
	 */
	protected function getFilters($filtersInt)
	{
		$filtersArray = array();
		switch ($filtersInt) {
			case 1:
				array_push($filtersArray,array('name' => 'stringTrim'));
				break;
			case 2:
				array_push($filtersArray,array('name' => 'stripTags'));
				break;
			case 3:
				array_push($filtersArray,array('name' => 'stringTrim'),array('name' => 'stripTags'));
				break;
			case 4:
				array_push($filtersArray,array('name' => 'htmlEntities'));
				break;
			case 5:
				array_push($filtersArray,array('name' => 'stringTrim'),array('name' => 'htmlEntities'));
				break;
			case 6:
				array_push($filtersArray,array('name' => 'stripTags'),array('name' => 'htmlEntities'));
				break;
			case 7:
				array_push($filtersArray,array('name' => 'stringTrim'),array('name' => 'stripTags'),array('name' => 'htmlEntities'));
				break;
			case 8:
				array_push($filtersArray,array('name' => 'stripNewLines'));
				break;
			case 9:
				array_push($filtersArray,array('name' => 'stringTrim'),array('name' => 'stripNewLines'));
				break;
			case 10:
				array_push($filtersArray,array('name' => 'stripTags'),array('name' => 'stripNewLines'));
				break;
			case 11:
				array_push($filtersArray,array('name' => 'stringTrim'),array('name' => 'stripTags'),array('name' => 'stripNewLines'));
				break;
			case 12:
				array_push($filtersArray,array('name' => 'htmlEntities'),array('name' => 'stripNewLines'));
				break;
			case 13:
				array_push($filtersArray,array('name' => 'stringTrim'),array('name' => 'htmlEntities'),array('name' => 'stripNewLines'));
				break;
			case 14:
				array_push($filtersArray,array('name' => 'stripTags'),array('name' => 'htmlEntities'),array('name' => 'stripNewLines'));
				break;
			case 15:
			default:
				array_push($filtersArray,array('name' => 'stringTrim'),array('name' => 'stripTags'),array('name' => 'htmlEntities'),array('name' => 'stripNewLines'));
		}

		return $filtersArray;
	}
	
	/**
	 * 创建图片名
	 * @param string $fileName
	 * @return string
	 */
	protected function createRandFileName()
	{
		return time().floor(microtime() * 10000).rand(10, 99);
	}
	
	/**
	 * 上传成功后，返回的图片路径
	 * @param array $file 文件$_FILE
	 * @return string
	 */
	protected function getUploadPath(Array $file)
	{
		return 'upload/'.str_replace($GLOBALS['UPLOADPATH'],'',$file['tmp_name']);
	}
	
	/**
	 * 文件mineType验证(inputFilter的数组内定义存在BUG，在此使用Callback调用此方法代替)
	 * @param string $value
	 * @return boolean
	 */
	protected function fileMimeType()
	{
		return function($value){
			$mimeType = new \Zend\Validator\File\MimeType($this->fileMimeType);
			return $mimeType->isValid($value);
		};
	}
	
	/**
	 * 设置数据库适配器
	 * @param \Zend\Db\Adapter\Adapter $dbAdapter
	 */
	public function setDbAdapter(\Zend\Db\Adapter\Adapter $dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
	}
	
	/**
	 * 设置inputFilter
	 * @param InputFilterInterface $inputFilter
	 */
	protected function setModel(InputFilterInterface $inputFilter)
	{
		$this->setInputFilter($inputFilter);
	}
	
	/**
	 * 获得inputFilter
	 * @param array $inputArray inputFilter设置数组
	 * @return \Zend\InputFilter\InputFilter
	 */
	protected function getModel(Array $inputArray)
	{
		$this->inputArray = $inputArray;
		return $this->getInputFilter();
	}
	
	/**
	 * 设置上传单文件对大容量
	 * @param string $fileMaxSize
	 */
	public function setFileMaxSize($fileMaxSize)
	{
		$this->fileMaxSize = $fileMaxSize;
	}
	
	/**
	 * 设置上传文件mimeType
	 * @param string $fileMimeType
	 */
	public function setFileMimeType($fileMimeType)
	{
		$this->fileMimeType = $fileMimeType;
	}
	
	/**
	 * 设置inputFilter主体程序
	 * @see \Zend\InputFilter\InputFilterAwareInterface::setInputFilter()
	 */
	public function setInputFilter(InputFilterInterface $inputFilter) {}
	
	/**
	 * 获得inputFilter主体程序
	 * @see \Zend\InputFilter\InputFilterAwareInterface::getInputFilter()
	 */
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

			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
}