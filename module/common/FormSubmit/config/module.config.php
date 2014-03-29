<?php
return array(
	'FormSubmit/init' => array(
		'dbModelName'          => 'DbSql',//数据库操作模块名
		'dbInsertFunction'     => 'add',//插入函数名
		'insertExistsFunction' => 'getExists',//插入数据存在检查函数名
		'dbUpdateFunction'     => 'edit',//更新函数名
		'updateExistsFunction' => 'getById',//更新数据存在检查函数名
		'validateModelName'    => 'Validate',//验证模块名
		'validateFunction'     => 'vailidAll',//验证函数名
		'submitMethod'         => 'post'
	)
);
