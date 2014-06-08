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
	private $attachmentFiles;
	protected $events;
	protected $template = false;
	protected $templatePath;
	protected $templateParams;
	
	function __construct($init,$template)
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
		isset($template['path']) && $this->templatePath = $template['path'];
		$this->events = new EventManager();
	}
	
	/**
	 * 设置模板文件
	 * @param string $template
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
	}
	
	/**
	 * 设置模板路径
	 * @param string $path
	 */
	public function setTemplatePath($path)
	{
		$this->templatePath = $path;
	}
	
	/**
	 * 设置模板参数
	 * @param array $params
	 */
	public function setTemplateParams(Array $params)
	{
		$this->templateParams = $params;
	}
	
	public function setAttachment(Array $files)
	{
		$this->attachmentFiles = $files;
	}
	
	private function getAttachment(&$mimeMessage)
	{
		$files = $this->attachmentFiles;
		if(count($files)){
			foreach($files as $file) {
				if(is_file($file)) {
					$data = fopen($file,'r');
					$messageAttachment = new \Zend\Mime\Part($data);
					$baseName = new \Zend\Filter\BaseName();
					$messageAttachment->filename = $baseName->filter($file);
					$messageAttachment->encoding = \Zend\Mime\Mime::ENCODING_BASE64;
					$messageAttachment->disposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT;
					$mimeMessage->addPart($messageAttachment);
				}
				else {
					throw new \Exception("smtpMail get attachment $file not found");
					return false;
				}
			}
		}
		
		return true;
	}
	
	/**
	 * 获得模板输出内容
	 * @return string
	 */
	private function getTemplateHtml()
	{
		$renderer = new \Zend\View\Renderer\PhpRenderer();
		
		$map = new \Zend\View\Resolver\TemplateMapResolver(array(
			'SendMail/Template' => $this->templatePath.$this->template,
		));
		
		$resolver = new \Zend\View\Resolver\AggregateResolver();
		$resolver->attach($map);
		$renderer->setResolver($resolver);
		
		$model = new \Zend\View\Model\ViewModel($this->templateParams);
		$model->setTemplate('SendMail/Template');
		 
		return $renderer->render($model);
	}
	
	/**
	 * smtp发送邮件
	 * @param string $to 接收方邮件地址
	 * @param string $title 邮件标题
	 * @param string $sendhtml 邮件正文
	 * @return boolean
	 */
    public function smtpMail($to,$title,$sendhtml = false)
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
    	
    	$this->template !== false && $sendhtml === false && $sendhtml = $this->getTemplateHtml();
    	$html = new \Zend\Mime\Part($sendhtml);
    	$html->type = $this->type;
    	$body = new \Zend\Mime\Message();
    	$body->addPart($html);
    	$setAtt = $this->getAttachment($body);   	
    	if($setAtt === false) return false;
    	
    	$message->setBody($body);
    	$transport->setOptions($options);
    	
	    try {
	    	$transport->send($message);
    	}
    	catch (\Exception $e) {
    		$this->events->trigger('setLog', null, array('model' => 'sendMail','message' => $e->getMessage(),'level' => 'WARN','fileName' => __FILE__,'line' => __LINE__));
    		throw new \Exception($e->getMessage());
    		return false;
    	}
    	
    	return true;
    }

}
