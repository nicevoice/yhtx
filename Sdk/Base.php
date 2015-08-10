<?php
class Sdk_Base
{
    private static $_iUseTime = 0;

    private static $_iOptCnt = 0;

    private static $_hCurl = null;

    /**
     * POST请求
     * @param string $sDomain 域名，如www,cms
     * @param string $sRoute 路由，如/news/index?id=1,
     * @param array $aParam 参数，array('id'=>1)
     * @return Ambigous <boolean, multitype:boolean string , mixed>
     *                  fdasfsa
     */
    public static function post($sDomain, $sRoute, $aParam = array(), $iCacheTime = 0)
    {
        return self::curl($sDomain, $sRoute, $aParam, 'post', $iCacheTime);
    }

    public static function getDebugStat ()
    {
        if (self::$_iOptCnt > 0) {
            return '[API]->Request: ' . self::$_iOptCnt . ', Use Time:' . self::$_iUseTime;
        } else {
            return '';
        }
    }

    private static function curl($sDomain, $sRoute, $aParam, $method, $iCacheTime)
    {
        if ($iCacheTime > 0) {
            $sCacheKey = md5($sDomain . $sRoute . serialize($aParam) . $method);
            $aRet = Util_Common::getCache()->get($sCacheKey);
            if (!empty($aRet)) {
                return $aRet;
            }
        }

        $time1 = microtime(true);
        $aParam['_time'] = time();
        $aParam['_sign'] = md5($aParam['_time'] . Yaf_G::getConf('signkey', 'sdk'));
        $sHost = Yaf_G::getConf($sDomain, 'sdkdomain');
        if (! $sHost) {
            throw new Exception('配置[sdkdomain][' . $sDomain . ']未找到！');
            return false;
        }
        $sRoute = trim($sRoute, '/?& ');
        $sUrl = 'http://' . $sHost . '/' . $sRoute;
        if (! self::$_hCurl) {
            self::$_hCurl = curl_init();
        }

        curl_setopt(self::$_hCurl, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
        curl_setopt(self::$_hCurl, CURLOPT_HEADER, false);
        curl_setopt(self::$_hCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(self::$_hCurl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt(self::$_hCurl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt(self::$_hCurl, CURLOPT_TIMEOUT, 30);
        if ($method == 'post') {
            curl_setopt(self::$_hCurl, CURLOPT_POST, 1);
            curl_setopt(self::$_hCurl, CURLOPT_POSTFIELDS, http_build_query($aParam));
        } else {
            $sUrl .= strpos($sUrl, '?') === false ? '?' : '&';
            $sUrl .= http_build_query($aParam);
            $aParam = null;
        }

        $aCookie = array();
        if (Yaf_G::getEnv() == 'beta') {
            $aCookie[] = 'fjdp_version=beta';
        }
        if (Yaf_G::isDebug()) {
            $aCookie[] = 'debug=2k9j38h#4';
        }
        if (! empty($aCookie)) {
            curl_setopt(self::$_hCurl, CURLOPT_COOKIE, join('; ', $aCookie));
        }

        curl_setopt(self::$_hCurl, CURLOPT_URL, $sUrl);
        $sData = curl_exec(self::$_hCurl);



        $aRet = array('status' => false, 'data' => '数据请求失败，返回为空！');

        if (! empty($sData)) {
            $aData = json_decode($sData, true);

            if (isset($aData['code'])) {//兼容mapi没有status
                if ($aData['code'] === 0) {
                    $aData['status'] = true;
                } else {
                    $aData['status'] = false;
                    //unset($aData['msg']);
                }
                if(isset($aData['data'])) {
                    $aData['data']['msg'] = $aData['msg'];
                }else {
                    $aData['data'] = array('msg' => $aData['msg']);
                }

                unset($aData['msg']);
                unset($aData['code']);
            }

            if (isset($aData['status']) && isset($aData['data'])) {
                $aRet = $aData;
            } else {
                $aRet = array('status' => false, 'data' => '数据请求错误:' . $sData);
            }
        }
        $oDebug = Util_Common::getDebug();
        if ($oDebug) {
            $time2 = microtime(true);
            $use_time = round(($time2 - $time1) * 1000, 2);
            self::$_iOptCnt++;
            self::$_iUseTime += $use_time;
            $oDebug->groupCollapsed('Api: ' . $sUrl . ' ' . $use_time . '毫秒');
            if (!empty($aParam)) {
                $oDebug->debug($aParam);
            }
            $oDebug->debug($aRet['data']);
            if (isset($aRet['debug'])) {
                foreach ($aRet['debug'] as $v) {
                    if (is_array($v)) {
                        $oDebug->add($v[0], $v[1]);
                    } else {
                        $oDebug->groupCollapsed($v);
                    }
                }
                $oDebug->groupEnd();
                unset($aRet['debug']);
            }
            $oDebug->groupEnd();
        }

        if ($iCacheTime > 0 && $aRet['status']) {
            Util_Common::getCache()->set($sCacheKey, $aRet, $iCacheTime);
        }

        return $aRet;
    }
}