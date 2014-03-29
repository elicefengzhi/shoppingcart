<?php

//定义serviceManager工厂
return array(
    'factories' => array(
        'Validate' => 'Validate\Service\ValidateFactory',
    	'adapter' =>  function($sm) {
    		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    		return $dbAdapter;
    	},
    ),
);