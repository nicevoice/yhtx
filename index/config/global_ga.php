<?php
require __DIR__ . '/global_common.php';

//Redis配制
$config['redis']['orm'] = array(
    'host' => '10.0.16.8',
    'port' => 6379,
    'db' => 0
);

//Redis配制
$config['redis']['bll'] = array(
    'host' => '10.0.16.8',
    'port' => 6379,
    'db' => 0
);


//Cache配制
$config['cache']['bll'] = array(
    array(
        'host' => '10.0.16.3',
        'port' => 11211,
    ),
    array(
        'host' => '10.0.16.4',
        'port' => 11211,
    )
);

//CRIC转换前的数据库配制
$config['mssql']['cric_xf'] = array(
    'host' => '10.0.8.8',
    'user' => 'fjreader',
    'pass' => '1z2xftp3',
    'dbname' => 'CricXinFang'
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

//crawl data数据库配制
$config['database']['crawl_data']['master'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=crawl_data',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);
$config['database']['crawl_data']['salve'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=crawl_data',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);



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

return $config;
