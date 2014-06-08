<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'ShoppingCart\Controller\ShoppingCart' => 'ShoppingCart\Controller\ShoppingCartController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'shopping-cart' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/shoppingCart',
                    'defaults' => array(
                        '__NAMESPACE__' => 'ShoppingCart\Controller',
                        'controller'    => 'ShoppingCart',
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
        'template_path_stack' => array(
            'ShoppingCart' => __DIR__ . '/../view',
        ),
    ),
);
