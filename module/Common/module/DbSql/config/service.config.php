<?php

//定义serviceManager工厂和数据库适配器
return array(
    'factories' => array(
        'DbSql'   => 'DbSql\Service\DbFactory',
    	'adapter' =>  function($sm) {
    		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    		return $dbAdapter;
    	},
    ),
);