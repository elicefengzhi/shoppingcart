<?php

//定义serviceManager工厂
return array(
    'factories' => array(
        'Fsession' => 'Fsession\Service\FsessionFactory',
    	'adapter' =>  function($sm) {
    		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    		return $dbAdapter;
    	},
    ),
);