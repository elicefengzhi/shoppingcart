<?php

//定义serviceManager工厂
return array(
    'factories' => array(
        'FormSubmit' => 'FormSubmit\Service\FormSubmitFactory',
    	'adapter' =>  function($sm) {
    		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    		return $dbAdapter;
    	},
    ),
);