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
                    	'module'     => 'Admin',
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
                				'module'     => 'Admin',
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
                						'module'     => 'Admin',
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
                						'module'     => 'Admin',
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
                				'module'     => 'Admin',
                				'controller' => 'Admin\Controller\News',
                				'action'     => 'index',
                			),
                		),
                		'may_terminate' => true,
                		'child_routes' => array(
                			'index' => array(
                				'type'    => 'Segment',
                				'options' => array(
                					'route'    => '[:pageNum[/]]',
                					'constraints' => array(
                						'pageNum' => '\d+'
                					),
                					'defaults' => array(
                						'module' => 'Admin',
                						'action' => 'index',
                					),
                				),
                			),
                			'add' => array(
                				'type'    => 'Segment',
                				'options' => array(
                					'route'    => 'add[/]',
                					'defaults' => array(
                						'module' => 'Admin',
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
                						'module' => 'Admin',
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
                						'module' => 'Admin',
                						'action' => 'show',
                					),
                				),
                			),
                			'delete' => array(
                				'type'    => 'Segment',
                				'options' => array(
                					'route'    => 'delete[/]',
                					'defaults' => array(
                						'module' => 'Admin',
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
                				'module'        => 'Admin',
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
                						'module' => 'Admin',
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
                						'module' => 'Admin',
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
                				'module'        => 'Admin',
                				'controller'    => 'Admin\Controller\Page',
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
                						'pageNum' => '\d+'
                					),
                					'defaults' => array(
                						'module' => 'Admin',
                						'action' => 'index',
                					),
                				),
                			),
                			'add' => array(
                				'type'    => 'Segment',
                				'options' => array(
                					'route'    => 'add[/]',
                					'defaults' => array(
                						'module' => 'Admin',
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
                						'module' => 'Admin',
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
                						'module' => 'Admin',
                						'action' => 'show',
                					),
                				),
                			),
                			'delete' => array(
                				'type'    => 'Segment',
                				'options' => array(
                					'route'    => 'delete[/]',
                					'defaults' => array(
                						'module' => 'Admin',
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
                				'module' => 'Admin',
                				'controller'    => 'Admin\Controller\Product',
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
                						'pageNum' => '\d+'
                					),
                					'defaults' => array(
                						'module' => 'Admin',
                						'action' => 'index',
                					),
                				),
                			),
                			'add' => array(
                				'type'    => 'Segment',
                				'options' => array(
                					'route'    => 'add[/]',
                					'defaults' => array(
                						'module' => 'Admin',
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
                						'module' => 'Admin',
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
                						'module' => 'Admin',
                						'action' => 'delete',
                					),
                				),
                			),
                			'forum' => array(
                				'type'    => 'Segment',
                				'options' => array(
                					'route'    => 'forum[/]',
                					'defaults' => array(
                						'module' => 'Admin',
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
                				'module' => 'Admin',
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
                						'module' => 'Admin',
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
	                					'module' => 'Admin',
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
                				'module' => 'Admin',
                				'controller'    => 'Admin\Controller\ProductType',
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
	                					'pageNum' => '\d+'
	                				),
	                				'defaults' => array(
	                					'module' => 'Admin',
	                					'action' => 'index',
	                				),
	                			),
	                		),
	                		'add' => array(
	                			'type'    => 'Segment',
	                			'options' => array(
	                				'route'    => 'add[/]',
	                				'defaults' => array(
	                					'module' => 'Admin',
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
	                					'module' => 'Admin',
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
                						'module' => 'Admin',
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
                				'module' => 'Admin',
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
                						'module' => 'Admin',
                						'action'        => 'show',
                					),
                				),
                			),
                			'delete' => array(
                				'type'    => 'Segment',
                				'options' => array(
                					'route'    => 'delete[/]',
                					'defaults' => array(
                						'module' => 'Admin',
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
    		'admin/layout'         		    => BASEPATH . 'theme/default/admin/pc/layout/main.phtml',
    		'admin/error/layout'		    => BASEPATH . 'theme/default/admin/pc/layout/error.phtml',
    		'admin/login/layout'		    => BASEPATH . 'theme/default/admin/pc/layout/login.phtml',
    		'error/admin/404'      		    => BASEPATH . 'theme/default/admin/pc/error/404.phtml',
    		'error/admin/index'    		    => BASEPATH . 'theme/default/admin/pc/error/500.phtml',
    		'admin/common/pagination'  	    => BASEPATH . 'theme/default/admin/pc/common/pagination.phtml',
    		'admin/common/errorMessage'     => BASEPATH . 'theme/default/admin/pc/common/errorMessage.phtml',
    		'admin/common/formErrorMessage' => BASEPATH . 'theme/default/admin/pc/common/formErrorMessage.phtml',
    	),
        'template_path_stack' => array(
            'Admin' => BASEPATH . 'theme/default/admin/pc/',
        ),
    ),
	'viewHelper/dispatch' => 'Admin',
);
