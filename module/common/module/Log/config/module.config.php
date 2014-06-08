<?php

//日志模块全局配置
return array(
	'log/init' => array(
		'path'          => BASEPATH,//日志文件夹所在目录
		'directoryName' => 'Log',//日志文件夹名
		'fileName'      => 'site.log',//日志文件名
		'maxSize'       => '1MB',//最大容量
		'isPigeonhole'  => true//是否开启自动归档
	)
);
