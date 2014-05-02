<?php
return array(
	'Fsession/init' => array(
		'name' => 'shoppingcart',
		'storage' => 'file',//file或database
		'database' => array(
			'tableName' => 'session',
			'dataColumnName' => 'data',
			'idColumnName' => 'id',
			'lifetimeColumnName' => 'lifetime',
			'modifiedColumnName' => 'modified',
			'nameColumnName' => 'name',
		),
		'config' => array(
			'remember_me_seconds' => 86400,//秒
			'cookie_lifetime'     => 86400,
			'gc_maxlifetime'      => 86400,
		),
	)
);
