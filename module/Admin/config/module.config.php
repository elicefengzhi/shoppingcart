<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
        	'Admin\Controller\News' => 'Admin\Controller\NewsController',
        	'Admin\Controller\Order' => 'Admin\Controller\OrderController',
        	'Admin\Controller\Page' => 'Admin\Controller\PageController',
        	'Admin\Controller\Product' => 'Admin\Controller\ProductController',
        	'Admin\Controller\ProductImage' => 'Admin\Controller\ProductImageController',
        	'Admin\Controller\ProductType' => 'Admin\Controller\ProductTypeController',
        	'Admin\Controller\Query' => 'Admin\Controller\QueryController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin[/]',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                	'index' => array(
                		'type'    => 'Segment',
                		'options' => array(
                			'route'    => '[index][/]',
                			'defaults' => array(
                				'controller' => 'Admin\Controller\Index',
                				'action'     => 'index',
                			),
                		),
                		'may_terminate' => true,
                		'child_routes' => array(
                			'login' => array(
                				'type'    => 'Segment',
                				'options' => array(
                					'route'    => 'login[/]',
                					'defaults' => array(
                						'controller' => 'Admin\Controller\Index',
                						'action'     => 'login',
                					),
                				),
                			),
                			'logout' => array(
                				'type'    => 'Segment',
                				'options' => array(
                					'route'    => 'logout[/]',
                					'defaults' => array(
                						'controller' => 'Admin\Controller\Index',
                						'action'     => 'logout',
                					),
                				),
                			),
                		),
                	),
                	'news' => array(
                		'type'    => 'Segment',
                		'options' => array(
                			'route'    => 'news[/]',
                			'defaults' => array(
                				'controller' => 'Admin\Controller\News',
                				'action'     => 'index',
                			),
                		),
                		'may_terminate' => true,
                		'child_routes' => array(
                			'index' => array(
                				'type'    => 'Segment',
                				'options' => array(
                					'route'    => '[:pageNum][/]',
                					'constraints' => array(
                						'pageNum' => '\d*'
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
                						'nId' => '\d*'
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
                						'nId' => '\d*'
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
                	'order' => array(
                		'type'    => 'Segment',
                		'options' => array(
                			'route'    => 'order[/]',
                			'defaults' => array(
                				'controller'    => 'Admin\Controller\Order',
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
                	'page' => array(
                		'type'    => 'Segment',
                		'options' => array(
                			'route'    => 'page[/]',
                			'defaults' => array(
                				'controller'    => 'Admin\Controller\Page',
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
                	'product' => array(
                		'type'    => 'Segment',
                		'options' => array(
                			'route'    => 'product[/]',
                			'defaults' => array(
                				'controller'    => 'Admin\Controller\Product',
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
                					'route'    => 'edit/[:pId[/]]',
                					'constraints' => array(
                						'pId' => '\d*'
                					),
                					'defaults' => array(
                						'action' => 'edit',
                					),
                				),
                			),
                			'delete' => array(
                				'type'    => 'Segment',
                				'options' => array(
                					'route'    => 'delete/[:pId[/]]',
                					'constraints' => array(
                						'pId' => '\d*'
                					),
                					'defaults' => array(
                						'action' => 'delete',
                					),
                				),
                			),
                			'forum' => array(
                				'type'    => 'Segment',
                				'options' => array(
                					'route'    => 'forum[/]',
                					'defaults' => array(
                						'action' => 'forum',
                					),
                				),
                			),
                		),
                	),
                	'product-image' => array(
                		'type'    => 'Segment',
                		'options' => array(
                			'route'    => 'productImage[/]',
                			'defaults' => array(
                				'controller'    => 'Admin\Controller\ProductImage',
                				'action'        => 'index',
                			),
                		),
                		'may_terminate' => true,
                		'child_routes' => array(
                			'index' => array(
                				'type'    => 'Segment',
                				'options' => array(
                					'route'    => 'index[/:pId][/]',
                					'constraints' => array(
                						'pId' => '\d+',
                					),
                					'defaults' => array(
                						'action' => 'index',
                					),
                				),
                			),
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
                	'product-type' => array(
                		'type'    => 'Segment',
                		'options' => array(
                			'route'    => 'productType[/]',
                			'defaults' => array(
                				'controller'    => 'Admin\Controller\ProductType',
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
	                				'route'    => 'edit/[:typeId[/]]',
	                				'constraints' => array(
	                					'typeId' => '\d+'
	                				),
	                				'defaults' => array(
	                					'action' => 'edit',
	                				),
	                			),
	                		),
	                		'delete' => array(
                				'type'    => 'Segment',
                				'options' => array(
                					'route'    => 'delete[/]',
                					'constraints' => array(
                						'typeId' => '\d+'
                					),
                					'defaults' => array(
                						'action' => 'delete',
                					),
                				),
                			),
                		),
                	),
                	'query' => array(
                		'type'    => 'Segment',
                		'options' => array(
                			'route'    => 'query[/]',
                			'defaults' => array(
                				'controller'    => 'Admin\Controller\Query',
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
        ),
    ),
    'view_manager' => array(
    	'layout' => 'admin/layout',
    	'not_found_template'       => 'error/admin/404',
    	'exception_template'       => 'error/admin/index',
    	'template_map' => array(
    		'admin/layout'         		=> __DIR__ . '/../view/pc/layout/main.phtml',
    		'admin/error/layout'		=> __DIR__ . '/../view/pc/layout/error.phtml',
    		'admin/login/layout'		=> __DIR__ . '/../view/pc/layout/login.phtml',
    		'error/admin/404'      		=> __DIR__ . '/../view/pc/error/404.phtml',
    		'error/admin/index'    		=> __DIR__ . '/../view/pc/error/500.phtml',
    		'admin/common/paging'  		=> __DIR__ . '/../view/pc/common/paging.php',
    		'admin/common/errorMessage' => __DIR__ . '/../view/pc/common/errorMessage.php',
    	),
        'template_path_stack' => array(
            'Admin' => __DIR__ . '/../view/pc/',
        ),
    ),
	'viewHelper/dispatch' => 'Admin',
);
