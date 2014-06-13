<?php
/**
 * 全局模块配置
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
            'layout/layout'        		=> __DIR__ . '/../view/layout/front/front.phtml',
        	'admin/layout'         		=> __DIR__ . '/../view/layout/admin/admin.phtml',
        	'admin/error/layout'		=> __DIR__ . '/../view/layout/admin/error.phtml',
        	'admin/login/layout'		=> __DIR__ . '/../view/layout/admin/login.phtml',
            'error/404'           		=> __DIR__ . '/../view/error/404.phtml',
            'error/index'          		=> __DIR__ . '/../view/error/500.phtml',
        	'error/admin/404'      		=> __DIR__ . '/../view/error/admin_404.phtml',
        	'error/admin/index'    		=> __DIR__ . '/../view/error/admin_500.phtml',
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
	//是否开启更改移动端试图层路径
	'isChangeMobileViewPath' => false,
	//自定义错误处理策略：false(不处理)，file写文件，email发邮件
	'errorStrategy' => array(
		'type' => 'file',
		'email' => array(
			'to' => '1095247806@qq.com'
		),
	),
);
