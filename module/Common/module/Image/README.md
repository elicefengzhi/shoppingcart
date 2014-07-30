#控制器中调用
$this->serviceLocator->get('Image')->ResizeImage()
     ->setImagePrefix('500_')
     ->Resize('img/51eb3ff3ac11f.jpg',array('width' => 500,'height' => 500));
#扩展加载优先级
--module.config.php
'extensionOrder' => array('imagick','gmagick','gd')
