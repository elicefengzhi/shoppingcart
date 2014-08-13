<?php

return array(
	'view_manager' => array(
		'display_not_found_reason' => true,
		'display_exceptions'       => false,
		'doctype'                  => 'HTML5',
		'strategies' => array (
			'ViewJsonStrategy'//添加json策略
		)
	),
	
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
	
	'controller_plugins' => array(
		'invokables' => array(
			'ViewHelper' => 'ViewHelper\ViewHelper\ViewHelper',
		)
	),
	
	'service_manager' => array(
		'factories' => array(
			'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
		),
	),
	
	'translator' => array(
		'locale' => 'en_US',
		'translation_file_patterns' => array(
			array(
				'type'     => 'gettext',
				'base_dir' => __DIR__ . '/../../module/Application/language',
				'pattern'  => '%s.mo',
			),
		),
	),
);
