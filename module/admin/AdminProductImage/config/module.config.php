<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'AdminProductImage\Controller\AdminProductImage' => 'AdminProductImage\Controller\AdminProductImageController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin-product-image' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/productImage[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AdminProductImage\Controller',
                        'controller'    => 'AdminProductImage',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'delete' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'delete[/:imageId][/]',
                            'constraints' => array(
                                'imageId' => '\d+',
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
            'AdminProductImage' => __DIR__ . '/../view',
        ),
    ),
);
