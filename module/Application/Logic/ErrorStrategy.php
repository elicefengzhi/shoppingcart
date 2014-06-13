<?php

namespace Application\Logic;

class ErrorStrategy
{
	public function errorHandle($applicationConfig,$exception,$sm)
	{
		if(isset($exception) !== false) {
			$className = get_class($exception);
			//获取错误相关信息
			$errorString = \Zend\Debug\Debug::dump('-----------------------------------------------------------','',false);
			$errorFileName = \Zend\Debug\Debug::dump($exception->getFile(),'File Name:',false);
			$errorFileLine = \Zend\Debug\Debug::dump($exception->getLine(),'File Line:',false);
			$server = \Zend\Debug\Debug::dump($_SERVER,'Server info:',false);
			isset($_SESSION) ? $session = \Zend\Debug\Debug::dump($_SESSION,'Session info:',false) : $session = null;
			$cookie = \Zend\Debug\Debug::dump($_COOKIE,'Cookie info:',false);
			$errorMassage = \Zend\Debug\Debug::dump($exception->getMessage(),'Error Message:',false);
			$errorTraceAsString = \Zend\Debug\Debug::dump($exception->getTraceAsString(),'Trace Error Info:',false);
			$errorString = $errorString.$errorFileName.$errorFileLine.$server.$session.$cookie.$errorMassage.$errorTraceAsString.$errorString;

			//错误处理
			if(isset($applicationConfig['errorStrategy'])) {
				isset($applicationConfig['errorStrategy']['type']) ? $type = $applicationConfig['errorStrategy']['type'] : $type = false;
				if($type == 'file') {
					$log = $sm->get('SetLog');
					$log->setFileName('error.log');
					$log->write('ERROR',$errorString,'WARN');
				}
				else if($type == 'email') {
					if(isset($applicationConfig['errorStrategy']['email'])) {
						$errorStrategyEmail = $applicationConfig['errorStrategy']['email'];
						if(isset($errorStrategyEmail['to'])) {
							$sm->get('SendMail')->smtpMail($errorStrategyEmail['to'],'error email',$errorString);
						}
					}
				}
			}
		}
	}
}