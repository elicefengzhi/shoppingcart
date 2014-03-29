<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'AdminProduct\Controller\AdminProduct' => 'AdminProduct\Controller\AdminProductController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-product' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/product[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AdminProduct\Controller',
                        'controller'    => 'AdminProduct',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                	'index' => array(
                		'type'    => 'Segment',
                		'options' => array(
                			'route'    => '[index][/:pageNum][/]',
                			'constraints' => array(
                				'pageNum' => '\d+'
                			),
                			'defaults' => array(
                				'action' => 'index',
                			),
                		),
                	),
                    'add' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'add[/]',
                            'defaults' => array(
                            	'action' => 'add',
                            ),
                        ),
                    ),
                	'edit' => array(
                		'type'    => 'Segment',
                		'options' => array(
                			'route'    => 'edit/[:pId[/]]',
                			'constraints' => array(
                				'pId' => '\d*'
                			),
                			'defaults' => array(
                				'action' => 'edit',
                			),
                		),
                	),
                	'delete' => array(
                		'type'    => 'Segment',
                		'options' => array(
                			'route'    => 'delete/[:pId[/]]',
                			'constraints' => array(
                				'pId' => '\d*'
                			),
                			'defaults' => array(
                				'action' => 'delete',
                			),
                		),
                	),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'AdminProduct' => __DIR__ . '/../view',
        ),
    ),
);
