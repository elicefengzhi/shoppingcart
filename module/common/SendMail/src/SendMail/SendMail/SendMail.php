<?php

namespace SendMail\SendMail;

use Zend\EventManager\EventManager;

class SendMail
{
	private $host;
	private $from;
	private $username;
	private $password;
	private $type;
	private $isSsl;
	private $ssl;
	private $port;
	private $coding;
	private $connectionClass;
	protected $events;
	
	function __construct($init)
	{
		isset($init['host']) && $this->host = $init['host'];
		isset($init['from']) && $this->from = $init['from'];
		isset($init['username']) && $this->username = $init['username'];
		isset($init['password']) && $this->password = $init['password'];
		isset($init['type']) && $this->type = $init['type'];
		isset($init['isSsl']) && $this->isSsl = $init['isSsl'];
		isset($init['ssl']) && $this->ssl = $init['ssl'];
		isset($init['port']) && $this->port = $init['port'];
		isset($init['coding']) && $this->coding = $init['coding'];
		isset($init['connectionClass']) && $this->connectionClass = $init['connectionClass'];
		$this->events = new EventManager();
	}
	
	/**
	 * smtp发送邮件
	 * @param string $to 接收方邮件地址
	 * @param string $title 邮件标题
	 * @param string $sendhtml 邮件正文
	 * @return boolean
	 */
    public function smtpMail($to,$title,$sendhtml)
    {
    	if(trim((string)$this->host) == '' || trim((string)$this->from) == '' || trim((string)$this->username) == '' || trim((string)$this->password) == '' || trim((string)$this->connectionClass) == '') {
    		throw new \Exception("smtpMail params error");
    		return false;
    	} 
    	
    	$message = new \Zend\Mail\Message();
    	trim((string)$this->coding) != '' && $message->setEncoding($this->coding);
    	$message->addTo($to)
    	->addFrom($this->from)
    	->setSubject($title);
    	
    	$transport = new \Zend\Mail\Transport\Smtp();
    	$connectionConfig = array('username' => $this->username,'password' => $this->password);
    	if($this->isSsl === true) {
    		$connectionConfig['ssl'] = $this->ssl;
    	}
    	$options = new \Zend\Mail\Transport\SmtpOptions(array(
            'host' => $this->host,
            'connection_class' => $this->connectionClass,
    		'connection_config' => $connectionConfig,
    		'port' => $this->port,
    	));
    	
    	$html = new \Zend\Mime\Part($sendhtml);
    	$html->type = $this->type;
    	$body = new \Zend\Mime\Message();
    	$body->addPart($html);
    	$message->setBody($body);
    	$transport->setOptions($options);
    	
	    try {
	    	$transport->send($message);
    	}
    	catch (\Exception $e) {
    		throw new \Exception($e->getMessage());
    		$this->events->trigger('setLog', null, array('model' => 'sendMail','message' => $e->getMessage(),'level' => 'WARN','fileName' => __FILE__,'line' => __LINE__));
    		return false;
    	}
    	
    	return true;
    }

}
