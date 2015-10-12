<?php
//Permission数据库配制
$config['database']['permission']['master'] = array(
		'dsn' => 'mysql:host=127.0.0.1;dbname=qa_permission',
		'user' => 'root',
		'pass' => 'xjc.123',
		'init' => array(
			'SET CHARACTER SET utf8',
			'SET NAMES utf8'
		)
);
$config['database']['permission']['salve'] = array(
    'dsn' => 'mysql:host=127.0.0.1;dbname=qa_permission',
    'user' => 'root',
    'pass' => 'xjc.123',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

//CMS数据库配制
$config['database']['cms']['master'] = array(
    'dsn' => 'mysql:host=127.0.0.1;dbname=qa_cms',
    'user' => 'root',
    'pass' => 'xjc.123',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);
$config['database']['cms']['salve'] = array(
    'dsn' => 'mysql:host=127.0.0.1;dbname=qa_cms',
    'user' => 'root',
    'pass' => 'xjc.123',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
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

//SFTP配置
$config['sftp']['account_check'] = array(
    "host"          => "192.168.10.18",
    "user"          => "www",
    "port"          => "22",
    "pubkey_path"  => "/data1/www/.ssh/id_rsa.pub",
    "privkey_path" => "/data1/www/.ssh/id_rsa",
);

$config['sftp']['topic'] = array(
    "host"          => "172.28.28.73",
    "user"          => "www",
    "port"          => "22",
    "pubkey_file"  => "/data/www/.ssh/id_rsa.pub",
    "privkey_file" => "/data/www/.ssh/id_rsa",
);

$config['sftp']['topic_path'] = "/data/www/topic/";


$config['es']['bll'] = array(
    'hosts' => array(
        '172.28.28.73:9200'
    )
);

return $config;