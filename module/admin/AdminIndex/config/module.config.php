<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'AdminIndex\Controller\AdminIndex' => 'AdminIndex\Controller\AdminIndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-index' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AdminIndex\Controller',
                        'controller'    => 'AdminIndex',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'login[/]',
                            'defaults' => array(
                            	'__NAMESPACE__' => 'AdminIndex\Controller',
                            	'controller'    => 'AdminIndex',
                            	'action'        => 'login',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'AdminIndex' => __DIR__ . '/../view',
        ),
    ),
	'viewHelper/dispatch' => 'Admin',
);
