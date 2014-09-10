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
		
	//资产管理配置
	'asset_manager' => array(
		'resolver_configs' => array(
			'collections' => array(
				'js/main.min.js' => array(
					'js/jsq/jquery-1.10.2.min.js',
					'js/pt/png_ie6_fix.js',
					'js/glb.js',
					'js/lang/message.js',
					'js/lang/lang.js',
				),
				'css/main.min.css' => array(
					'css/c1.css',
				),
			),
			'paths' => array(
				BASEPATH.'theme/default',
			),
		),
		'caching' => array(
			'js/main.min.js' => array(
				'cache'   => 'Assetic\\Cache\\FilesystemCache',
				'options' => array(
					'dir' => 'data/cache/js',
				),
			),
			'css/main.min.css' => array(
				'cache'   => 'Assetic\\Cache\\FilesystemCache',
				'options' => array(
					'dir' => 'data/cache/css',
				),
			),
		),
		'filters' => array(
			'js/main.min.js' => array(
				array(
					'filter' => 'JSMin', 
				),
			),
			'css/main.min.css' => array(
				array(
					'filter' => 'CssMin',
				),
			),
		),
	),
);
