<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';

// 设置常量
defined('BASEPATH') || define('BASEPATH', realpath(__DIR__.'/../').'/');
defined('APPLICATIONPATH') || define('APPLICATIONPATH', BASEPATH.'module/Application/');
defined('BASEURL') || define('BASEURL', '/');

$applicationEnv = 'local';
$GLOBALS['UPLOADPATH'] = BASEPATH.'public/upload/';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.'.$applicationEnv.'.php')->run();
