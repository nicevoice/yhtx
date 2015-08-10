<?php
require dirname(__FILE__). '/global_common.php';

//Redis配制
$config['redis']['orm'] = array(
    'host' => '127.0.0.1',
    'port' => 6379,
    'db' => 0
);
//Redis配制
$config['redis']['bll'] = array(
    'host' => '127.0.0.1',
    'port' => 6379,
    'db'   => 0
);

//Cache配制
$config['cache']['bll'] = array(
    array(
        'host' => '127.0.0.1',
        'port' => 11211,
    )
);

return $config;