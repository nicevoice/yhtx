<?php
//yhtx数据库配制
$config['database']['yhtx']['master'] = array(
		'dsn' => 'mysql:host=127.0.0.1;dbname=yhtx',
		'user' => 'zfx',
		'pass' => '111111',
		'init' => array(
			'SET CHARACTER SET utf8',
			'SET NAMES utf8'
		)
);

$config['database']['yhtx']['salve'] = array(
    'dsn' => 'mysql:host=127.0.0.1;dbname=yhtx',
    'user' => 'zfx',
    'pass' => '111111',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

$config['database']['qdm167674328_db']['master'] = array(
    'dsn' => 'mysql:host=qdm167674328.my3w.com;dbname=qdm167674328_db',
    'user' => 'qdm167674328',
    'pass' => 'Zfxlovehll1314',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

$config['database']['qdm167674328_db']['salve'] = array(
    'dsn' => 'mysql:host=115.28.173.226;dbname=qdm167674328_db',
    'user' => 'qdm167674328',
    'pass' => 'Zfxlovehll1314',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

$config['database']['permission']['master'] = array(
    'dsn' => 'mysql:host=127.0.0.1;dbname=permission',
    'user' => 'root',
    'pass' => 'xjc.123',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);
$config['database']['permission']['salve'] = array(
    'dsn' => 'mysql:host=127.0.0.1;dbname=permission',
    'user' => 'root',
    'pass' => 'xjc.123',
    'init' => array(
        'SET CHARACTER SET utf8',
        'SET NAMES utf8'
    )
);

return $config;