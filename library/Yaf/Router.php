<?php

/**
 * Yaf_Router is the standard framework router.
 */
class Yaf_Router
{

    /**
     * Find a matching route to the current Request and inject
     * returning values to the Request object.
     *
     * @return bool if there is a valid route
     */
    public function route (Yaf_Request_Abstract $request)
    {
        $requestUri = $request->getRequestUri();

        //去掉根目录
        $baseuri = $request->getBaseUri();
        if ($requestUri != '' && $baseuri != '' && stripos($requestUri, $baseuri) === 0) {
            $path = substr($requestUri, strlen($baseuri));
        } else {
            $path = $requestUri;
        }

        //支持rewrite路由
        $matches = null;
        $rewrites = Yaf_G::getConf('rewrite', 'route');
        $rest = explode('?', $path, 2);
        $path = $rest[0];
        if (!empty($rewrites)) {
            foreach ($rewrites as $k => $v) {
                $matches = null;
                if (preg_match($k, $path, $matches)) {
                    $path = preg_replace($k, $v, $path);
                    break;
                }
            }
        }
        if (!empty($rest[1])) {
            $path .= '?' . $rest[1];
        }

        //取得Route
        $aRoute = Yaf_G::getRoute($path);
        $request->setModuleName($aRoute['module']);
        $request->setControllerName($aRoute['controller']);
        $request->setActionName($aRoute['action']);

        //解析参数
        $params = array();
        $rest = $aRoute['rest'];
        $numSegs = count($rest);
        if ($numSegs > 0) {
            for ($i = 0; $i < $numSegs; $i = $i + 2) {
                $key = urldecode($rest[$i]);
                $val = isset($rest[$i + 1]) ? urldecode($rest[$i + 1]) : null;
                $params[$key] = (isset($params[$key]) ? (array_merge((array) $params[$key], array(
                    $val
                ))) : $val);
            }
        }
        //rewrite参数解析
        if (!empty($matches)) {
            foreach ($matches as $k => $v) {
                if (!is_numeric($k)) {
                    $params[$k] = $v;
                }
            }
        }
        $request->setParam($params);

        return true;
    }
}
