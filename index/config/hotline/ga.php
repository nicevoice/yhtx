<?php
//数据库配制
$config['database']['hotline']['master'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=hotline',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

$config['database']['hotline']['salve'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=hotline',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

//Redis配制
$config['redis']['orm'] = array(
    'host' => '10.0.16.8',
    'port' => 6379,
    'db' => 0
);

$config['cache']['orm'] = array(
    array(
        'host' => '10.0.16.3',
        'port' => 11211,
    ),
    array(
        'host' => '10.0.16.4',
        'port' => 11211,
    )
);

//日志配置
$config['logger']['path'] = '/data/app/app_logs';

return $config;