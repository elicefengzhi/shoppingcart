#控制器中调用(例)
#假设验证方法名为vailidAll
$this->serviceLocator->get('Validate')->AdminIndex()->vailidAll(数据源)

#验证类的验证主方法
#1.
public function vailidAll($data)
{
	return $this->validate(
		$data,
		array('newsTitle' => 'news_title','newsBody' => 'news_body'),//key为验证方法名，value为对其传参(不传参为null)
		array('news_title' => null,'news_body' => null)//初始化返回数据
	);
}
#2.
public function vailidAll($data)
{
	$this->sourceData = $data;//数据源
	$this->initParams();//初始化返回数据方法，可有可无
	
	try {
		$this->val1($param);//调用验证方法
	}
	catch (\Exception $e){
		$this->isTry = false;
		throw new \Exception($e->getMessage());
		$this->setLog($e->getMessage(),'DEBUG',__FILE__,__LINE__);
	}
	
	return $this->ReturnData();
}

#验证返回值
验证的返回参数是$this->data内的数据
#验证失败的条件(任意一条)：
1.$this->errorMessage有值
2.$this->isTry为false
3.$this->isDataError为false

#快速验证
if($this->getRequest()->isPost()){
	$newsTitle = $this->params()->fromPost('news_title');
	$newsBody = $this->params()->fromPost('news_body');
	$quick = array(
		'news_title' => array(
			'data' => $newsTitle,
			'notEmpty' => array('message' => '不为空'),
			'stringLength' => array('min' => 1,'max' => 5,'message' =>'过长')
		),
		'news_body' => array(
			'data' => $newsBody,
			'stringLength' => array('min' => 0,'max' => 3,'message' =>'过长')
		),
	);
	$validate = $this->serviceLocator->get('Validate')->QuickValidate();
	$val = $validate->validate($quick);
}
