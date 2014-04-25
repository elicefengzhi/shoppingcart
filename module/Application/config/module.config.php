<?php
/**
 * 全局模块配置
 * 配置全局布局页、404页、500页等信息
 * 
 * 注意：
 * display_exceptions为是否开启框架报错，因不同开发环境的考虑，这里是false。
 * 可以在local.php中为当前环境重置此选项。
 */

return array(
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => false,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'        		=> __DIR__ . '/../view/layout/front.phtml',
        	'admin/layout'         		=> __DIR__ . '/../view/layout/admin.phtml',
            'error/404'           		=> __DIR__ . '/../view/error/404.phtml',
            'error/index'          		=> __DIR__ . '/../view/error/500.phtml',
        	'admin_error/404'      		=> __DIR__ . '/../view/error/admin_404.phtml',
        	'admin_error/index'    		=> __DIR__ . '/../view/error/admin_500.phtml',
        	'admin/common/paging'  		=> __DIR__ . '/../view/common/admin/paging.php',
        	'admin/common/errorMessage' => __DIR__ . '/../view/common/admin/errorMessage.php',
        	'front/common/paging'  		=> __DIR__ . '/../view/common/front/paging.php',
        ),
        'strategies' => array (
        	'ViewJsonStrategy'//添加json策略
        )
    ),
	'controller_plugins' => array(
		'invokables' => array(
			'ViewHelper' => 'ViewHelper\ViewHelper\ViewHelper',
		)
	),
);
