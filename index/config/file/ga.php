<?php
//数据库配制
$config['database']['dfs_db']['master'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=dfs_db',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

$config['database']['dfs_db']['salve'] = array(
    'dsn' => 'mysql:host=10.0.16.2;dbname=dfs_db',
    'user' => 'webuser',
    'pass' => 'webuser@cric_Cx',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

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

// 图片类型
$config['file']['aImageType'] = array(
    'gif',
    'jpg',
    'png'
);


//文件系统支持的文件格式
$config['file']['aAllowedType'] = array(
    '/ipo\.com|fangjiadp\.com|cric\.com|fangjia\-crm\.com|enjoytouch\.com\.cn/' => array(
        'gif',
        'jpg',
        'png',
        'pdf',
        'doc',
        'docx',
        "flv",
        "swf",
        "mp4",
    )
);


//文件系统支持的文件格式
$config['file']['aAllowedViewType'] = array(
    '/ipo\.com|fangjiadp\.com|cric\.com|fangjia\-crm\.com|enjoytouch\.com\.cn/' => array(
        'gif',
        'jpg',
        'png',
        'pdf',
        'doc',
        'docx'
    )
);

//文件系统支持的文件格式
$config['file']['aAllowedDownloadType'] = array(
    '/ipo\.com|fangjiadp\.com|cric\.com|fangjia\-crm\.com|enjoytouch\.com\.cn/' => array(
        'gif',
        'jpg',
        'png',
        'pdf',
        'doc',
        'docx',
        'zip'
    )
);

//文件系统支持的大小
$config['file']['aAllowedSize'] = array(
    '/ipo\.com|fangjiadp\.com|cric\.com|fangjia\-crm\.com|enjoytouch\.com\.cn/' => array(
        'iMin' => 1,
        'iMax' => 15728640
    )
);

//文件系统开放的域名
$config['file']['aAllowedDomain'] = array(
    '/fangjiadp\.com|ipo\.com|cric\.com|fangjia\-crm\.com|enjoytouch\.com\.cn/'
);

//文件系统存储
$config['file']['aStorageHost'] = array(
    array(
        'iHostID' => 1,
        'sRouteKeys' => array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'),
    ),
    array(
        'iHostID' =>2,
        'sRouteKeys' => array('a', 'b', 'c', 'd', 'd', 'e', 'f', 'g', 'h','i', 'j', 'k',
            'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        )
    )
);

$config['file']['browseCache'] = 315360000;
$config['file']['sRawDir'] = '/data/www/dfs/raw'; // 文件存储位置

//日志配置
$config['logger']['path'] = '/data/app/app_logs';

return $config;