<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Index\Controller\Index' => 'Index\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'index' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/[index[/]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Index\Controller',
                        'controller'    => 'Index',
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
            'Index' => __DIR__ . '/../view',
        ),
    ),
);
