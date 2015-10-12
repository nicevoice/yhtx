<?php
//Permission数据库配制
$config['database']['permission']['master'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=permission',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);
$config['database']['permission']['salve'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=permission',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

//CMS数据库配制
$config['database']['cms']['master'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=cms',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);
$config['database']['cms']['salve'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=cms',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
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


//crm data数据库配制
$config['database']['crm']['master'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=crm',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);
$config['database']['crm']['salve'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=crm',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

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

//CRIC用户库
$config['mssql']['FangJiaDpUser'] = array(
    'host' => '10.0.8.9',
    'user' => 'fjrandw',
    'pass' => 'UP78ew4Xvu',
    'dbname' => 'FangJiaDpUser'
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

$config['topic']['path'] = "/data/app/wwwroot/news/topic/";

//日志配置
//$config['logger']['path'] = '/data/app/app_logs';

$config['es']['bll'] = array(
    'hosts' => array(
        '172.28.28.73:9200'
    )
);

$config['cityPhone'] = array(
    'shanghai' => '15109269691', //
    'xiamen' => '18650003217',
    'fuzhou' => '13774505684',
    'hangzhou'=> array('13641984576','13588725577'),
    'ningbo' => '13736016644',
    'wuxi'   => '13961822214',
    'nanjing' => '13585082349',
    'hefei' => '17756083200',
    'suzhou'=> '15862478550',
    'changzhou' => '13916909276',
    'changsha' => '18602109016',
    'guangzhou' => '15920157995',
    'shenzhen' => array('18019563166','18688989810'),
    'nanning' => '13916189575',
    'haikou' => '18559313377',
    'wuhan' => '13720307700',
    'zhengzhou' => '18603851717',
    'beijing' => '18610096080',
    'qindao' => '18561977650',
    'tianjin'=> '18622129538',
    'changchun' => '13514461310',
    'shenyang' => '13700056200',
    'dalian' => '18641189985',
    'jinan' => '18602171171',
    'haerbin' => '15904604317',
    'chengdu' => '13882287508',
    'chongqing' => '18623333797',
    'xian' => '13060386377',
    'guiyang' => '13671906177',
    'kunming' => '13301631113',
);
$config['cricEvaluationUrl'] = 'http://cric.yiju.org/NBXTi/CMSReport.ashx';


//百度问答数据库配制
$config['database']['bd_qa']['master'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=bd_qa',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',

    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);
$config['database']['bd_qa']['salve'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=bd_qa',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

return $config;