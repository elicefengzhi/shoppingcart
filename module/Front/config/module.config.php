<?php
return array(
    'controllers' => array(
        'invokables' => array(
        	'Front\Controller\Index' => 'Front\Controller\IndexController',
        	'Front\Controller\Product' => 'Front\Controller\ProductController',
        	'Front\Controller\ShoppingCart' => 'Front\Controller\ShoppingCartController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'index' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '[/]',
                    'defaults' => array(
                        'controller'    => 'Front\Controller\Index',
                        'action'        => 'index',
                    ),
                ),
            ),
        	'product' => array(
        		'type'    => 'Segment',
        		'options' => array(
        			'route'    => '/product[/]',
        			'defaults' => array(
        				'controller'    => 'Front\Controller\Product',
        				'action'        => 'index',
        			),
        		),
        		'may_terminate' => true,
        		'child_routes' => array(
        			'index' => array(
        				'type'    => 'Segment',
        				'options' => array(
        					'route'    => '[index][/]',
        					'defaults' => array(
        						'action' => 'index',
        					),
        				),
        			),
        			'show' => array(
        				'type'    => 'Segment',
        				'options' => array(
        					'route'    => 'show/:pId[/]',
        					'constraints' => array(
        						'pId' => '\d+'
        					),
        					'defaults' => array(
        						'action' => 'show',
        					),
        				),
        			),
        			'ajax' => array(
        				'type'    => 'Segment',
        				'options' => array(
        					'route'    => 'ajax/:type[/]',
        					'constraints' => array(
        						'type' => '[a-zA-Z]+'
        					),
        					'defaults' => array(
        						'action' => 'ajax',
        					),
        				),
        			),
        		),
        	),
        	'shopping-cart' => array(
        		'type'    => 'Segment',
        		'options' => array(
        			'route'    => '/shoppingCart',
        			'defaults' => array(
        				'controller'    => 'Front\Controller\ShoppingCart',
        				'action'        => 'index',
        			),
        		),
        		'may_terminate' => true,
        		'child_routes' => array(
        			'index' => array(
        				'type'    => 'Segment',
        				'options' => array(
        					'route'    => '[index][/]',
        					'defaults' => array(
        						'action' => 'index',
        					),
        				),
        			),
        			'add' => array(
        				'type'    => 'Segment',
        				'options' => array(
        					'route'    => 'add/:pId[/]',
        					'constraints' => array(
        						'pId' => '\d+'
        					),
        					'defaults' => array(
        						'action' => 'add',
        					),
        				),
        			),
        			'clear' => array(
        				'type'    => 'Segment',
        				'options' => array(
        					'route'    => 'clear[/]',
        					'defaults' => array(
        						'action' => 'clear',
        					),
        				),
        			),
        		),
        	),
        ),
    ),
    'view_manager' => array(
    	'view_manager' => array(
    		'layout' => 'front/layout',
    		'not_found_template'       => 'error/404',
    		'exception_template'       => 'error/index',
    		'template_map' => array(
    			'front/layout'        => __DIR__ . '/../view/pc/layout/main.phtml',
    			'error/404'           => __DIR__ . '/../view/pc/error/404.phtml',
    			'error/index'         => __DIR__ . '/../view/pc/error/500.phtml',
    			'front/common/paging' => __DIR__ . '/../view/pc/common/paging.php',
    		),
    	),
        'template_path_stack' => array(
            'Front' => __DIR__ . '/../view/pc/',
        ),
    ),
);
