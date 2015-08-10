<?php
if ($argc < 2) {
    echo "命令格式错误，请按下面格式运行：\n";
    echo "{$_SERVER['_']} {$argv[0]} url [env]\n";
    exit;
}

date_default_timezone_set ('Asia/Shanghai');
$aUrl = parse_url($argv[1]);
$sHost = $aUrl['host'];
$sRouteUri = isset($aUrl['path'])?$aUrl['path']:'/';
$sRouteUri .= isset($aUrl['query'])?'?'.$aUrl['query']:'';
$sIndexPath = __DIR__;
define('ENV_CMD_HOST', $sHost);
define('ENV_CMD_MAIN', realpath(__FILE__));

$aHost = explode('.', $sHost);
define('ENV_CHANNEL', array_shift($aHost));

if (count($aHost) == 4) {
    define('ENV_CLOUDNAME', $aHost[0]);
    define('ENV_SCENE', $aHost[1]);
    define('STATIC_VERSION', '');
    define("APP_PATH", '/data/www/' . ENV_CLOUDNAME . '/' . ENV_CHANNEL . '/app');
    define("LIB_PATH", '/data/www/' . ENV_CLOUDNAME . '/' . ENV_CHANNEL . '/library');
} else {
    define('ENV_CLOUDNAME', '');
    define('ENV_SCENE', isset($argv[3]) && $argv[3] == 'beta' ? $argv[3] : 'ga');

    $sVersionFile = $sIndexPath.'/version/'.ENV_CHANNEL.'.' . ENV_SCENE;

    if (!file_exists($sVersionFile)) {
        die('No version file for ' . ENV_CHANNEL);
    }
    define('VERSION', trim(file_get_contents($sVersionFile)));

    define("APP_PATH", '/data/app/wwwroot/' . ENV_CHANNEL . '/' . VERSION . '/app');
    define("LIB_PATH", '/data/app/wwwroot/' . ENV_CHANNEL . '/' . VERSION . '/library');

    $sStaticVersionFile = $sIndexPath . '/version/static.' . ENV_SCENE;
    if (! file_exists($sStaticVersionFile)) {
        die('File ' . $sStaticVersionFile . ' not found!');
    }
    define('STATIC_VERSION', trim(file_get_contents($sStaticVersionFile)));
}

define('ENV_DOMAIN', join('.', $aHost));
define("CONF_PATH", $sIndexPath . '/config/');

try {
    require_once LIB_PATH . '/loader.php';
    $app = new Yaf_Application();
    $app->bootstrap()->run();
} catch (Exception $e) {
    echo $e->getMessage();
}