<?php

return array(
	//是否开启更改移动端试图层路径
	'isChangeMobileViewPath' => true,
		
	//自定义错误处理策略：false(不处理)，file写文件，email发邮件
	'errorStrategy' => array(
		'type' => false,
		'email' => array(
			'to' => '1095247806@qq.com'
		),
	),
	
	//基础模型配置
	'baseModel' => array(
		'upload' => array(
			'fileMaxSize' => '2MB',
			'fileMimeType' => array('image/gif,image/jpg,image/jpeg,image/pjpeg,image/png,image/x-png')
		)
	),
);
