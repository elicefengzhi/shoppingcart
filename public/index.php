<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// 设置常量
defined('APPLICATION_ENV') || define('APPLICATION_ENV', 'local');
defined('BASEPATH') || define('BASEPATH', realpath(__DIR__.'/../').'/');
defined('APPLICATIONPATH') || define('APPLICATIONPATH', BASEPATH.'module/Application/');
defined('BASEURL') || define('BASEURL', '/');

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.'.APPLICATION_ENV.'.php')->run();
