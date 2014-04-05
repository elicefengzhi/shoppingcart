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
                    'route'    => '/admin/page',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AdminPage\Controller',
                        'controller'    => 'AdminPage',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
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
