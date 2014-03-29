<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'AdminProductType\Controller\AdminProductType' => 'AdminProductType\Controller\AdminProductTypeController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-product-type' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/productType[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AdminProductType\Controller',
                        'controller'    => 'AdminProductType',
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
                			'route'    => 'edit/[:typeId[/]]',
                			'constraints' => array(
                				'typeId' => '\d+'
                			),
                			'defaults' => array(
                				'action' => 'edit',
                			),
                		),
                	),
                	'delete' => array(
                		'type'    => 'Segment',
                		'options' => array(
                			'route'    => 'delete[/]',
                			'constraints' => array(
                				'typeId' => '\d+'
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
            'AdminProductType' => __DIR__ . '/../view',
        ),
    ),
);
