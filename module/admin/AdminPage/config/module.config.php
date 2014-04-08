<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'AdminPage\Controller\AdminPage' => 'AdminPage\Controller\AdminPageController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-page' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/page[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AdminPage\Controller',
                        'controller'    => 'AdminPage',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
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
                    'show' => array(
                		'type'    => 'Segment',
                		'options' => array(
            				'route'    => 'show/[:pId[/]]',
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
            'AdminPage' => __DIR__ . '/../view',
        ),
    ),
);
