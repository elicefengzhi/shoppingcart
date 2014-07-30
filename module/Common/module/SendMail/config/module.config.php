<?php
return array(
	'email/init' => array(
		'send' => array(
			'host'             => 'smtp.gmail.com',//发送方host
			'from'             => 'elicechaofeng@gmail.com',//发送方邮件地址
			'username'         => 'elicechaofeng@gmail.com',//发送方用户名
			'password'         => 'good6990',//发送方密码
			'type'             => 'text/html',//发送格式
			'isSsl'            => true,//是否启用ssl
			'ssl'              => 'tls',//ssl类型
			'port'             => '587',//发送方端口
			'coding'           => '',//邮件编码
			'connectionClass'  => 'plain',//connection class
		),
		'template' => array(
			'path' => __DIR__.'/../src/SendMail/Template/'//模板文件目录
		)
	),
);
