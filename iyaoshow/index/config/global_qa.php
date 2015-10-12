<?php
require __DIR__ . '/global_common.php';

//Redis配制
$config['redis']['orm'] = array(
    'host' => '127.0.0.1',
    'port' => 6379,
    'db' => 1
);
//Redis配制
$config['redis']['bll'] = array(
    'host' => '127.0.0.1',
    'port' => 6379,
    'db' => 1
);




//Cache配制
$config['cache']['bll'] = array(
    array(
        'host' => '127.0.0.1',
        'port' => 11311,
    )
);

//CRIC转换前的数据库配制
$config['mssql']['cric_xf'] = array(
    'host' => '172.28.28.40\sql2008',
    'user' => 'sa',
    'pass' => '1qaz!QAZ',
    'dbname' => 'CricXinFang'
);
//CRIC转换后的数据库配制
$config['database']['cric_xf']['master'] = array(
    'dsn' => 'mysql:host=127.0.0.1;dbname=cric_xf',
    'user' => 'root',
    'pass' => 'xjc.123',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);
$config['database']['cric_xf']['salve'] = array(
    'dsn' => 'mysql:host=127.0.0.1;dbname=cric_xf',
    'user' => 'root',
    'pass' => 'xjc.123',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

//crawl data数据库配制
$config['database']['crawl_data']['master'] = array(
    'dsn' => 'mysql:host=127.0.0.1;dbname=crawl_data',
    'user' => 'root',
    'pass' => 'xjc.123',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);
$config['database']['crawl_data']['salve'] = array(
    'dsn' => 'mysql:host=127.0.0.1;dbname=crawl_data',
    'user' => 'root',
    'pass' => 'xjc.123',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

return $config;