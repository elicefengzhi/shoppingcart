<?php

return array(
    'db' => array(
        'driver' => 'Pdo',
        'dsn'            => 'mysql:dbname=masemuki;host=127.0.0.1;prot=3306',
        'username'       => 'root',
        'password'       => '',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
);
