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
	'asset_manager' => array(
		'resolver_configs' => array(
			'paths' => array(
				__DIR__ . '/../../../theme/default',
			),
		),
	),
);
