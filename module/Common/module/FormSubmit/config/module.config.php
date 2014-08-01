<?php

/**
 * 如果是自定义数据库操作，则在此配置数据库操作模块相关信息
 * dbInsertFunction：执行添加的方法名
 * insertExistsFunction：添加时检查数据存在的方法名
 * dbUpdateFunction：执行更新的方法名
 * updateExistsFunction：更新时检查数据存在的方法名
 * 
 * 如果是自定义验证，则在此配置验证模块相关信息
 * validateFunction：验证方法名
 * 
 * 如果是快速验证提供方法名
 * quickValidateFunction：快速验证方法名
 */
return array(
	'FormSubmit/init' => array(
		'db' => array(
			'dbInsertFunction'     => 'add',//插入方法名
			'insertExistsFunction' => 'getExists',//插入数据存在检查方法名
			'dbUpdateFunction'     => 'edit',//更新方法名
			'updateExistsFunction' => 'getById',//更新数据存在检查方法名
		),
		'validate' => array(
			'validateFunction'	    => 'vailidAll',//验证方法名
			'errorMessageFunction'	=> 'ErrorMessage',//错误信息方法名
		),
		'media' => array(
			'uploadPath'       => BASEPATH.'public/upload/',//上传目录
			'minSize'          => 1,//最小容量
			'maxSize'          => '5MB',//最大容量
			'mimeType'         => array('image/jpg','image/jpeg','image/png','image/gif'),
		),
	)
);
