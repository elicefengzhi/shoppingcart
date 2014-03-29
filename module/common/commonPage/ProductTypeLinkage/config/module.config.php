<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'ProductTypeLinkage\Controller\ProductTypeLinkage' => 'ProductTypeLinkage\Controller\ProductTypeLinkageController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'product-type-linkage' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/commonPage/productTypeLinkage[/:parentId][/]',
                	'constraints' => array(
                		'parentId' => '\d+|-1'
                	),
                    'defaults' => array(
                        '__NAMESPACE__' => 'ProductTypeLinkage\Controller',
                        'controller'    => 'ProductTypeLinkage',
                        'action'        => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'ProductTypeLinkage' => __DIR__ . '/../view',
        ),
    ),
);
