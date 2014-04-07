<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'AdminQuery\Controller\AdminQuery' => 'AdminQuery\Controller\AdminQueryController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-query' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/query[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AdminQuery\Controller',
                        'controller'    => 'AdminQuery',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'show' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'show/[:qId][/]',
                            'constraints' => array(
                            	'qId' => '\d+'
                            ),
                            'defaults' => array(
                            	'action'        => 'show',
                            ),
                        ),
                    ),
                    'delete' => array(
                		'type'    => 'Segment',
                		'options' => array(
            				'route'    => 'delete[/]',
            				'defaults' => array(
            					'action'  => 'delete',
            				),
                		),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'AdminQuery' => __DIR__ . '/../view',
        ),
    ),
);
