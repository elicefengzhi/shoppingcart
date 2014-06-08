#控制器中调用(例)
$this->serviceLocator->get('SendMail')->smtpMail('aa@bb.com','test title','test body');

#模板使用
#注意模板文件默认放在模块目录下src/SendMail/Template
//在控制器中调用
$sendMail = $this->serviceLocator->get('SendMail');
$sendMail->setTemplate('register.phtml');
$sendMail->setTemplateParams(array('test' => 'this is a test email'));
$sendMail->smtpMail('test@test.com','this is a test email');
