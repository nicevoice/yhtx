<?php
$GLOBALS['INCLUDEFILE'] = array();
function yafAutoload($sClassName) {
    $sFile = join(DIRECTORY_SEPARATOR, explode('_', $sClassName)) . '.php';
    foreach (array(APP_PATH, LIB_PATH,ROOT_PATH, APP_PATH . '/..') as $sPath) {
        if (file_exists($sPath . '/' . $sFile)) {
            require_once $sPath . '/' . $sFile;

            $GLOBALS['INCLUDEFILE'][] = $sPath . '/' . $sFile;
            return true;
        }
    }
    $sError = '类【' . $sClassName . '】没有加载到！';
    if (version_compare(PHP_VERSION, '5.3.0') === - 1){//PHP_VERSION,phpversion()
        echo $sError;die;
    } else {
        throw new Exception($sError);
    }
}
spl_autoload_register('yafAutoload');
