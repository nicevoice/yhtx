<?php

class Yaf_G
{

    const YAF_CONTROLLER_DIRECTORY_NAME = 'Controller';

    const YAF_VIEW_DIRECTORY_NAME = 'View';

    const YAF_DEFAULT_VIEW_EXT = 'phtml';

    const YAF_ROUTER_DEFAULT_ACTION = 'index';

    const YAF_ROUTER_DEFAULT_CONTROLLER = 'Index';

    const YAF_ROUTER_DEFAULT_MODULE = 'Index';

    /**
     * 加载过的配制文件
     *
     * @var unknown
     */
    protected static $_configFiles = array();

    /**
     * 配制项
     *
     * @var unknown
     */
    protected static $_config = array();

    /**
     * 运行的开始时间
     *
     * @var unknown
     */
    protected static $_startTime = 0;

    /**
     * 初始化
     */
    public static function init ()
    {
        self::$_startTime = microtime(true);
        Yaf_G::loadConfig('common');//加载项目中整体配置
        Yaf_G::loadConfig(ENV_SCENE);//加载项目中环境配置
        Yaf_G::loadConfig('global_'.ENV_SCENE);//加载整总配置
    }

    /**
     * 取得运行的时间
     *
     * @return number
     */
    public static function getRunTime ()
    {
        return round((microtime(true) - self::$_startTime) * 1000, 2);
    }

    /**
     * 取得当前频道
     *
     * @return string
     */
    public static function getChannel ()
    {
        return ENV_CHANNEL;
    }

    /**
     * 取得当前环境
     *
     * @return string
     */
    public static function getEnv ()
    {
        return ENV_SCENE;
    }

    /**
     * 是否为debug环境
     *
     * @return boolean
     */
    public static function isDebug ()
    {
        if (isset($_COOKIE['debug']) && $_COOKIE['debug'] == '2k9j38h#4') {
            return true;
        }
        return (self::getEnv() == 'dev');
    }

    /**
     * 加载配制文件
     *
     * @param unknown $type
     * @throws Yaf_Exception
     */
    public static function loadConfig ($type)
    {
        if (file_exists(CONF_PATH . '/' . self::getChannel() . '/' . $type . '.php')) {
            $sFile = CONF_PATH . '/' . self::getChannel() . '/' . $type . '.php';
        } elseif (file_exists(CONF_PATH . '/' . $type . '.php')) {
            $sFile = CONF_PATH . '/' . $type . '.php';
        } elseif (file_exists(self::getConfPath() . '/' . $type . '.php')) {
            $sFile = self::getConfPath() . '/' . $type . '.php';
        } else {
            throw new Yaf_Exception('Config file ' . $type . ' not find!');
        }
        $config = self::$_config;
        include $sFile;
        self::$_config = $config;
        self::$_configFiles[$type] = 1;
    }

    /**
     * 取得配制内容
     *
     * @param unknown $key
     * @param string $types
     * @param string $file
     * @return NULL multitype:
     */
    public static function getConf ($key, $type = null, $file = null)
    {
        if ($file != null && ! isset(self::$_configFiles[$file])) {
            self::loadConfig($file);
        }
        if ($type != null) {
            if (isset(self::$_config[$type][$key])) {
                return self::$_config[$type][$key];
            } else if ($key == null && (isset(self::$_config[$type]))) {
                return self::$_config[$type];
            }

            return null;
        }

        if (isset(self::$_config[$key])) {
            return self::$_config[$key];
        }

        return null;
    }

    /**
     * 解析Route
     *
     * @param unknown $uri
     * @return multitype:string Ambigous <string, unknown>
     */
    public static function getRoute ($uri)
    {
        $module = self::YAF_ROUTER_DEFAULT_MODULE;
        $controller = self::YAF_ROUTER_DEFAULT_CONTROLLER;
        $action = self::YAF_ROUTER_DEFAULT_ACTION;

        //去掉参数，在$_GET里能获取到
        $aUrl = parse_url($uri);
        $path = preg_replace('/[\.\?].+$/', '', $uri);
        $path = explode('/', trim($path, '/'));
        $root = self::getControllerPath();
        if (!empty($path[0]) && is_dir($root . '/' . ucfirst($path[0]))) {
            $module = ucfirst(array_shift($path));
        }
        if (!empty($path[0]) && file_exists($root . '/' . $module . '/' . ucfirst($path[0]) . '.php')) {
            $controller = ucfirst(array_shift($path));
        }
        if (!empty($path[0])) {
            $action = array_shift($path);
        }

        $rest = $path;
        if (count($rest) % 2 == 1) {
            $rest[] = '';
        }
        
        //解析这种的Action: detail.id.184.html
        $tmp = explode('.', trim(strstr($uri, '.'), '.'));
        if (count($tmp) > 1) {
            $rest = $tmp;
            $rest[count($tmp) - 1] = strstr($rest[count($tmp) - 1], '?', true);
        }
        if (count($rest) % 2 == 1) {
            $rest[] = '';
        }
        
        if (isset($aUrl['query'])) {
            parse_str($aUrl['query'], $aParam);
            foreach ($aParam as $k => $v) {
                $rest[] = $k;
                $rest[] = $v;
            }
        }
        if (count($rest) % 2 == 1) {
            $rest[] = '';
        }

        return array(
            'path' => $root,
            'module' => $module,
            'controller' => $controller,
            'action' => $action,
            'rest' => $rest
        );
    }

    /**
     * Module + Controller + Action => URL
     *
     * @param unknown $sMca
     * @return string
     */
    public static function routeToUrl ($sRoute, $bFullPath = false)
    {
        $aMca = explode('_', $sRoute);
        $sUrl = '';
        if ($bFullPath || $aMca[1] != self::YAF_ROUTER_DEFAULT_MODULE) {
            $sUrl .= '/' . strtolower($aMca[1][0]) . substr($aMca[1], 1);
        }
        if ($bFullPath || $aMca[2] != self::YAF_ROUTER_DEFAULT_CONTROLLER) {
            $sUrl .= '/' . strtolower($aMca[2][0]) . substr($aMca[2], 1);
        }
        $sUrl .= '/' . $aMca[3];
        return $sUrl;
    }

    /**
     * 取得URL地址
     *
     * @param string $uri
     * @param string $params
     * @param string $domain
     * @param string $postfix
     * @return string
     */
    public static function getUrl ($uri = null, $params = null, $bFullPath = false, $domain = null, $postfix = null)
    {
        $url = '';
        if ($domain != null) {
            $url .= 'http://' . self::getConf($domain, 'domain');
        }
        if ($uri == null) {
            $oRequest = Yaf_Dispatcher::getInstance()->getRequest();
            $uri = $oRequest->getRequestUri();
        }
        $aRoute = self::getRoute($uri);
        $url = self::routeToUrl('Controller_' . $aRoute['module'] . '_' . $aRoute['controller'] . '_' . $aRoute['action'], $bFullPath);
        if ($postfix == null) {
            $delimiter = '/';
        } else {
            $delimiter = '.';
        }
        if (! empty($params)) {
            foreach ($params as $k => $v) {
                $url .= $delimiter . urlencode($k) . $delimiter . urlencode($v);
            }
        }
        return $url . $postfix;
    }

    /**
     * 取得Controller的路径
     *
     * @return string
     */
    public static function getControllerPath ()
    {
        return APP_PATH . '/' . self::YAF_CONTROLLER_DIRECTORY_NAME;
    }

    /**
     * 取得View的路径
     *
     * @return string
     */
    public static function getViewPath ()
    {
        return APP_PATH . '/' . self::YAF_VIEW_DIRECTORY_NAME;
    }

    /**
     * 取得配制文件路径
     *
     * @return string
     */
    public static function getConfPath ()
    {
        return APP_PATH . '/../conf';//app目录上一级下的conf目录
    }

    /**
     * 是否为绝对路径
     *
     * @param unknown $path
     * @return boolean
     */
    public static function isAbsolutePath ($path)
    {
        if (substr($path, 0, 1) == "/" || ((strpos($path, ":") !== false) && (strpos(PHP_OS, "WIN") !== false))) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 提取出异常错误里的详细信息 @param		object		$oExp		异常对象 @param		string		$sImp			换行符
     */
    public static function parseException ($oExp, $sImp = "\n")
    {
        $aMsg = array();
        $aMsg[] = '# 错误时间 => ' . date('Y-m-d H:i:s');
        if ($oExp->getCode() > 0) {
            $aMsg[] = '# 错误代码 => ' . $oExp->getCode();
        }
        $aMsg[] = '# 错误消息 => ' . $oExp->getMessage();
        $aMsg[] = '# 错误位置 => ' . $oExp->getFile() . '(' . $oExp->getLine() . '行)';
        $aEtrac = $oExp->getTrace();
        $iTotalEno = count($aEtrac) - 1;
        $iEno = 0;
        foreach ($aEtrac as $iEno => $aTrace) {
            $aMsg[] = '================================================================' . $sImp;
            $tmp = '第' . ($iTotalEno - $iEno) . '步 ';
            if(isset($aTrace['file'])) {
                $tmp .= '文件:' . $aTrace['file'] . ' (' . $aTrace['line'] . '行)';
            }

            $tmp .= $sImp . '函数名：';
            if (isset($aTrace['class'])) {
                $tmp .= $aTrace['class'] . '->';
            }
            $tmp .= $aTrace['function'] . '()';
            if (isset($aTrace['args']) && ! empty($aTrace['args'])) {
                $aTmpArg = array();
                foreach ($aTrace['args'] as $ano => $aArg) {
                    $atmp = $sImp . '@参数_' . $ano . '( ' . gettype($aArg) . ' ) = ';
                    if (is_numeric($aArg) || is_string($aArg)) {
                        $atmp .= $aArg;
                    } elseif (is_object($aArg)) {
                        $atmp .= get_class($aArg);
                    } else {
                        $atmp .= json_encode($aArg);
                    }
                    $aTmpArg[] = $atmp;
                }
                $tmp .= implode('', $aTmpArg);
            }
            $aMsg[] = $tmp;
        }
        return join($sImp, $aMsg);
    }
}
