<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Product\Controller\Product' => 'Product\Controller\ProductController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'product' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/product[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Product\Controller',
                        'controller'    => 'Product',
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
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Product' => __DIR__ . '/../view',
        ),
    ),
);
