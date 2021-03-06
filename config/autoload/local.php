<?php 

$dbParams = array(
	'database'  => 'shoppingcart',
	'username'  => 'root',
	'password'  => '',
	'hostname'  => 'localhost',
	'driver_options' => array(
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
	),
	// buffer_results - only for mysqli buffered queries, skip for others
	'options' => array('buffer_results' => true)
);

return array(
	'view_manager' => array(
		'display_exceptions' => true
	),
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname='.$dbParams['database'].';host='.$dbParams['hostname'],
		'database'       => $dbParams['database'],
		'username'       => $dbParams['username'],
		'password'       => $dbParams['password'],
		'hostname'       => $dbParams['hostname'],
		'driver_options' => $dbParams['driver_options'],
    ),

// 	'service_manager' => array(
// 		'factories' => array(
// 			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
// 		),
// 	),
		
	'service_manager' => array(
		'factories' => array(
			'Zend\Db\Adapter\Adapter' => function ($sm) use ($dbParams) {
				$adapter = new BjyProfiler\Db\Adapter\ProfilingAdapter(array(
					'driver'         => 'pdo',
					'dsn'            => 'mysql:dbname='.$dbParams['database'].';host='.$dbParams['hostname'],
					'database'       => $dbParams['database'],
					'username'       => $dbParams['username'],
					'password'       => $dbParams['password'],
					'hostname'       => $dbParams['hostname'],
					'driver_options' => $dbParams['driver_options'],
				));
				//php_sapi_name() == 'cli'
				if (true) {
					$logger = new Zend\Log\Logger();
					// write queries profiling info to stdout in CLI mode
					$writer = new Zend\Log\Writer\Stream('Log/site.log');
					//$writer = new Zend\Log\Writer\Stream('php://output');
					$logger->addWriter($writer, Zend\Log\Logger::DEBUG);
					$adapter->setProfiler(new BjyProfiler\Db\Profiler\LoggingProfiler($logger));
				} else {
					$adapter->setProfiler(new BjyProfiler\Db\Profiler\Profiler());
				}
				if (isset($dbParams['options']) && is_array($dbParams['options'])) {
					$options = $dbParams['options'];
				} else {
					$options = array();
				}
				$adapter->injectProfilingStatementPrototype($options);
				return $adapter;
			},
		),
	),
);