#控制器中调用(例)
//input的name为test
$files = $this->getRequest()->getFiles()->toArray();//获得上传图片
isset($files['test']) && $this->serviceLocator->get('ImageUpload')->upload($files['test'],'first/second');
#或
isset($files['test']) && $this->serviceLocator->get('ImageUpload')->upload($files['test']);
