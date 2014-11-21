<?php
//资产管理配置
return array(
	'asset_manager' => array(
		'resolver_configs' => array(
			'collections' => array(
				'js/main.min.js' => array(
					'js/jsq/jquery-1.10.2.min.js',
					'js/pt/png_ie6_fix.js',
					'js/glb.js',
					'js/lang/message.js',
					'js/lang/lang.js',
				),
				'css/main.min.css' => array(
					'css/c1.css',
				),
			),
			'paths' => array(
				BASEPATH.'theme/default',
			),
		),
		'caching' => array(
			'js/main.min.js' => array(
				'cache'   => 'Assetic\\Cache\\FilesystemCache',
				'options' => array(
					'dir' => 'data/cache/js',
				),
			),
			'css/main.min.css' => array(
				'cache'   => 'Assetic\\Cache\\FilesystemCache',
				'options' => array(
					'dir' => 'data/cache/css',
				),
			),
		),
		'filters' => array(
			'css/main.min.css' => array(
				array(
					'service' => 'urlEmbed',
				)
			),
			'js/main.min.js' => array(
				array(
					'service' => 'jsTemplate',
				)
			)
		),
		'add' => array(
			'jsTemplate' => array(
				'leftDelimiter' => '{{',
				'rightDelimiter' => '}}',
				'customKeyWords' => array()
			),
			'urlEmbed' => array(
				'baseUrl' => '/images/',
				'fileReplaceUrl'	=> '/images/',
			)
		)	
	),
		
	'service_manager' => array(
		'factories' => array(
			'urlEmbed' => function($sm) {
				$config = require __DIR__ .'/assetic.config.php';
				$config = $config['asset_manager']['add'];
				$urlEmbed = new \Application\Logic\AsseticAdd\UrlEmbed();
				$urlEmbed->setBaseUrl($config['urlEmbed']['baseUrl']);
				$urlEmbed->setFileReplaceUrl($config['urlEmbed']['fileReplaceUrl']);
				
				return $urlEmbed;
			},
			'jsTemplate' => function($sm) {
				$config = require __DIR__ .'/assetic.config.php';
				$config = $config['asset_manager']['add'];
				$jsTemplate = new \Application\Logic\AsseticAdd\JsTemplate();
				$jsTemplateHelper = new \Application\Logic\AsseticAdd\JsTemplateHelper();
				$jsTemplateHelper->setServiceManager($sm);
				$jsTemplateHelper->setConfig($config['jsTemplate']);
				$keyWords = $jsTemplateHelper->getReplaceWords();
				$jsTemplate->setConfig($config['jsTemplate']);
				$jsTemplate->setKeyWords($keyWords);

				return $jsTemplate;
			}
		),
	),
);