<?php
return array(
	'Image/init' => array(
		'saveBasePath' => BASEPATH.'upload/',
		'extensionOrder' => array('imagick','gmagick','gd')//加载扩展优先级
	)
);
