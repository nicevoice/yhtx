<?php
//数据库配制
$config['database']['xinfang']['master'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=xinfang',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

$config['database']['xinfang']['salve'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=xinfang',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

//二手房数据库配置
$config['database']['esf']['master'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=esf',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

$config['database']['esf']['salve'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=esf',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

//CRIC转换后的数据库配制
$config['database']['cric_xf']['master'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=cric_xf',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);
$config['database']['cric_xf']['salve'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=cric_xf',
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

return $config;