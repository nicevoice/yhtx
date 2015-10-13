<?php
//域名配置
/**
$config['domain']['www']        = 'www.' . ENV_DOMAIN;
$config['domain']['manage']     = 'manage.' . ENV_DOMAIN;
$config['domain']['permission'] = 'permission.' . ENV_DOMAIN;
$config['domain']['cms']        = 'cms.' . ENV_DOMAIN;
$config['domain']['static']     = 'static.' . ENV_DOMAIN . (STATIC_VERSION ? '/' . STATIC_VERSION : '');
$config['domain']['file']       = 'file.' . ENV_DOMAIN;
$config['domain']['jobmq']      = 'jobmq.' . ENV_DOMAIN;
$config['domain']['news']       = 'news.' . ENV_DOMAIN;
$config['domain']['touchweb']       = 'm.' . ENV_DOMAIN;
$config['domain']['fangjiadp']  = 'www.fangjiadp.com';
$config['domain']['mfangjiadp']  = 'm.fangjiadp.com';
*/
$config['domain']['image'] = 'image.'.ENV_DOMAIN;
$config['domain']['static']     = 'static.'.ENV_DOMAIN;
$config['domain']['www']     = ENV_HOST;

$config['switchVersion']['allowip'] = array(
    '101.231.183.230',
    '172.28.28.172',
    '172.28.28.213',
);

//URL配置
$config['url']['upload'] = 'http://' . $config['domain']['www'] . '/file/upload';
$config['url']['dfsview'] = 'http://' . $config['domain']['image'];

$config['url']['bannerupload'] = 'http://' . $config['domain']['file'] . '/file/bannerupload';


//SDK配制
$config['sdk']['signkey'] = 'loeik(*&3$cric#@#jiee';
foreach ($config['domain'] as $k => $v) {
    $config['sdkdomain'][$k] = $v;
}

//日志配置
$config['logger']['sBaseDir'] = '/data/app/app_logs';

$config['logger']['common'] = array(
    'sSplitType' => 'day',
    'sDir' => 'common',
    'iLevel' => 0
);
$config['logger']['cron'] = array(
    'sSplitType' => 'day',
    'sDir' => 'cron',
    'iLevel' => 0
);