#控制器中调用(例)
$this->serviceLocator->get('ViewHelper')->Front()->dataFormatter(false,time(),'Y-m-d');
#试图中调用(例)
$this->viewHelper()->string('testName',$item);//相应模块module.config.php中定义viewHelper/dispatch，才能正确调用指定自定义试图助手
