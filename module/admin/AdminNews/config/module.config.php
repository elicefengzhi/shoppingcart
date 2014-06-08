<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'AdminNews\Controller\AdminNews' => 'AdminNews\Controller\AdminNewsController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-news' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/news[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AdminNews\Controller',
                        'controller'    => 'AdminNews',
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
            				'route'    => 'edit/[:nId[/]]',
            				'constraints' => array(
            					'pId' => '\d*'
            				),
            				'defaults' => array(
            					'action' => 'edit',
            				),
                		),
                    ),
                    'show' => array(
                		'type'    => 'Segment',
                		'options' => array(
            				'route'    => 'show/[:nId[/]]',
            				'constraints' => array(
            					'pId' => '\d*'
            				),
            				'defaults' => array(
            					'action' => 'show',
            				),
                		),
                    ),
                    'delete' => array(
                		'type'    => 'Segment',
                		'options' => array(
            				'route'    => 'delete[/]',
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
            'AdminNews' => __DIR__ . '/../view',
        ),
    ),
);
