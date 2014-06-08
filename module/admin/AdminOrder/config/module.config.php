<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'AdminOrder\Controller\AdminOrder' => 'AdminOrder\Controller\AdminOrderController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-order' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/order[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AdminOrder\Controller',
                        'controller'    => 'AdminOrder',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'index' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[:pageNum][/]',
                            'constraints' => array(
                                'controller' => '\d+',
                            ),
                            'defaults' => array(
                            	'action' => 'index',
                            ),
                        ),
                    ),
                	'statusDelete' => array(
                		'type'    => 'Segment',
                		'options' => array(
                			'route' => 'statusDelete/:pageNum[/]',
                			'constraints' => array(
                				'controller' => '\d+',
                			),
                			'defaults' => array(
                				'action' => 'statusDelete',
                			),
                		),
                	),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'AdminOrder' => __DIR__ . '/../view',
        ),
    ),
);
