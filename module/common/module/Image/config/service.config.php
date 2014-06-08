<?php

//定义serviceManager工厂
return array(
    'factories' => array(
        'Image'   => 'Image\Service\ImageFactory',
    	'Image\Validate' =>  function($sm) {
    		return new \Image\Image\Validate();
    	},
    ),
);