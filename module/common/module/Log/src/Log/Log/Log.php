<?php

namespace Log\Log;

use Zend\Log\Writer\Stream;
use Zend\Log\Logger;

class Log 
{
	private $path;
	private $directoryName;
	private $fileName;
	private $maxSize;
	private $isPigeonhole;
	
	function __construct($init)
	{
		if($init !== false) {
			isset($init['path']) && $this->path = $init['path'];
			isset($init['directoryName']) && $this->directoryName = $init['directoryName'];
			isset($init['fileName']) && $this->fileName = $init['fileName'];
			isset($init['maxSize']) && $this->maxSize = $init['maxSize'];
			isset($init['isPigeonhole']) && $this->isPigeonhole = $init['isPigeonhole'];
		}
		else {
			throw new \Exception("init error");
		}
	}

	public function setPath($path)
	{
		$this->path = $path;
	}
	
	public function setDirectoryName($directoryName)
	{
		$this->directoryName = $directoryName;
	}
	
	public function setFileName($fileName)
	{
		$this->fileName = $fileName;
	}
	
	public function setMaxSize($maxSize)
	{
		$this->maxSize = $maxSize;
	}
	
	public function setIsPigeonhole($isPigeonhole)
	{
		$this->isPigeonhole = $isPigeonhole;
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
	 * 初始化
	 * @return boolean
	 */
	private function init()
	{	
		$fileName = $this->fileName;
		$path = $this->path;
		$directoryName = $this->directoryName;
		$logRealPath = $path.$directoryName.'/';
		if(trim((string)($fileName)) == '' || trim((string)($path)) == '' || trim((string)($directoryName)) == '') return false;
		if($this->mkdirs($logRealPath) === false) {
			throw new \Exception("mkdirs error");
			return false;
		}
		
		if($this->isPigeonhole === true) {
			//日志文件超过最大容量进行归档
			$validator = new \Zend\Validator\File\Size(array('max' => $this->maxSize));
			if(!$validator->isValid($logRealPath.$fileName)) {
				$filter = new \Zend\Filter\File\Rename($logRealPath.date('Y-m-d H:i:s').'.log');
				$filter->filter($logRealPath.$fileName);
			}	
		}
		
		return true;
	}
	
	/**
	 * 日志写入
	 * @param string $model 报错模块名
	 * @param string $message 报错信息
	 * @param string $level 报错级别(详细说明在下面)
	 * @param string $fileName 报错文件名<br/>
	 * 默认：false
	 * @param string $line 报错行数<br/>
	 * 默认：false
	 * @return boolean
	 * 
	 * 报错级别：
	 *  EMERG   = 0;  // Emergency: system is unusable
		ALERT   = 1;  // Alert: action must be taken immediately
		CRIT    = 2;  // Critical: critical conditions
		ERR     = 3;  // Error: error conditions
		WARN    = 4;  // Warning: warning conditions
		NOTICE  = 5;  // Notice: normal but significant condition
		INFO    = 6;  // Informational: informational messages
		DEBUG   = 7;  // Debug: debug messages
	 */
	public function write($model,$message,$level,$fileName = false,$line = false)
	{
		if($this->init() === false) {
			throw new \Exception("write init error");
			return false;
		}
		$writer = new Stream($this->path.$this->directoryName.'/'.$this->fileName);
		$string = "\n";
		$string.= 'time:'.date('Y-m-d H:i:s',time())."\n";
		$string.= 'level:'.$level."\n";
		$string.= 'model:'.$model."\n";
		$fileName !== false && $string.= 'fileName:'.$fileName."\n";
		$line !== false && $string.= 'line:'.$line."\n";
		$string.= 'message:'.$message."\n";

		$logger = new Logger();
		$logger->addWriter($writer);

		$logger->$level($string);
		
		return false;
	}
}
