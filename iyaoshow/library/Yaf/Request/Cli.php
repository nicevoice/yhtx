<?php

/**
 * Yaf_Request_Cli
 *
 */
class Yaf_Request_Cli extends Yaf_Request_Abstract
{

    public function __construct ()
    {
        global $sRouteUri;
        $requestUri = $sRouteUri;
        $this->setRequestUri($requestUri);
        if (isset($_SERVER['REQUEST_METHOD'])) {
            $method = $_SERVER['REQUEST_METHOD'];
        } else {
            $sapiType = php_sapi_name();
            if (strtolower($sapiType) == 'cli' || substr($sapiType, 0, 3) == 'cgi') {
                $method = 'CLI';
            } else {
                $method = 'unknown';
            }
        }
        $this->_method = $method;
    }
}
