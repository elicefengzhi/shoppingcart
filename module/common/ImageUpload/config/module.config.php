<?php

//图片上传模块全局配置
return array(
	'imageUpload/init' => array(
		'basePath'         => BASEPATH,//上传根目录
		'uploadPath'       => 'upload/',//上传目录
		'minSize'          => 1,//最小容量
		'maxSize'          => '5MB',//最大容量
		'sizeErrorMessage' => '请上传%s以内的图片',//容量报错信息
		'minWidth'         => 1,//最小宽度
		'maxWidth'         => '',//最大宽度
		'minHeight'        => 1,//最小高度
		'maxHeight'        => '',//最大高度
		'WHErrorMessage'   => '',//长宽报错信息
		'mimeType'         => array('image/jpg','image/jpeg','image/png','image/gif'),//允许图片类型
		'typeErrorMessage' => '请上传%s类型的图片',//类型报错信息
	)
);
