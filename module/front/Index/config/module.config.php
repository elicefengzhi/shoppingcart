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
                    'route'    => '[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Index\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
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
