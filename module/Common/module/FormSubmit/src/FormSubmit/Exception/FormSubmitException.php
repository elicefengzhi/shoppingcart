<?php

namespace FormSubmit\Exception;

use Zend\EventManager\EventManager;

class FormSubmitException extends \Exception
{
	//此处执行自定义错误处理
	function __construct($message)
	{
		$this->message = $message;
		$events = new EventManager();
		$events->trigger('setLog', null, array('model' => 'FormSubmit','message' => $this->getMessage(),'level' => 'WARN','fileName' => $this->getFile(),'line' => $this->getLine()));
	}	
}