<?php

/**
 *
 * Handle request object ...
 *
 */
abstract class Yaf_Request_Abstract
{

    /**
     * Module
     *
     * @var string
     */
    protected $_module;

    /**
     * Controller
     *
     * @var string
     */
    protected $_controller;

    /**
     * Action
     *
     * @var string
     */
    protected $_action;

    /**
     * Method
     *
     * @var string
     */
    protected $_method;

    /**
     * Has the action been dispatched?
     *
     * @var boolean
     */
    protected $_dispatched = false;

    /**
     * Request parameters
     *
     * @var array
     */
    protected $_params = array();

    /**
     * request_uri
     *
     * @var string
     */
    protected $_language;

    /**
     * routed
     *
     * @var string
     */
    protected $_routed;

    /**
     * REQUEST_URI
     *
     * @var string;
     */
    protected $_requestUri;

    /**
     * base_uri
     *
     * @var string
     */
    protected $_baseUri;

    /**
     * exception
     *
     * @var string
     */
    protected $_exception;

    /**
     * Retrieve the action name
     *
     * @return string
     */
    public function getActionName ()
    {
        return $this->_action;
    }

    public function getBaseUri ()
    {
        return $this->_baseUri;
    }

    public function setBaseUri ($baseUri = null)
    {
        return $this->_baseUri = $baseUri;
    }

    /**
     * Retrieve the controller name
     *
     * @return string
     */
    public function getControllerName ()
    {
        return $this->_controller;
    }

    /**
     * Retrieve a member of the $_ENV superglobal
     *
     * If no $key is passed, returns the entire $_ENV array.
     *
     * @param string $name            
     * @param mixed $default
     *            Default value to use if key not found
     *            
     * @return mixed Returns null if key does not exist
     */
    public function getEnv ($name = null, $default = null)
    {
        if (null === $name) {
            return $_ENV;
        }
        
        return (isset($_ENV[$name])) ? $_ENV[$name] : $default;
    }

    /**
     * Retrieve the exception
     *
     * @todo check if this is OK
     * @return string
     */
    public function getException ()
    {
        return $this->_exception;
    }

    /**
     * Retrieve the language
     *
     * @return string
     */
    public function getLanguage ()
    {
        if (null === $this->_language) {
            $this->_language = $this->getEnv('HTTP_ACCEPT_LANGUAGE');
        }
        
        return $this->_language;
    }

    /**
     * Retrieve the method
     *
     * @return string
     */
    public function getMethod ()
    {
        if (null === $this->_method) {
            $method = $this->getServer('REQUEST_METHOD');
            if ($method) {
                $this->_method = $method;
            } else {
                $sapiType = php_sapi_name();
                if (strtolower($sapiType) == 'cli' || substr($sapiType, 0, 3) == 'cgi') {
                    $this->_method = 'CLI';
                } else {
                    $this->_method = 'Unknown';
                }
            }
        }
        
        return $this->_method;
    }

    public function getModuleName ()
    {
        return $this->_module;
    }

    /**
     * Get an action parameter
     *
     * @param string $key            
     * @param mixed $default
     *            Default value to use if key not found
     *            
     * @return mixed
     */
    public function getParam ($name, $default = null)
    {
        $name = (string) $name;
        if (isset($this->_params[$name])) {
            return $this->_params[$name];
        }
        
        return $default;
    }

    /**
     * Get all action parameters
     *
     * @return array
     */
    public function getParams ()
    {
        return $this->_params;
    }

    public function getRequestUri ()
    {
        return $this->_requestUri;
    }

    /**
     * Retrieve a member of the $_SERVER superglobal
     *
     * If no $key is passed, returns the entire $_SERVER array.
     *
     * @param string $key            
     * @param mixed $default
     *            Default value to use if key not found
     *            
     * @return mixed Returns null if key does not exist
     */
    public function getServer ($name = null, $default = null)
    {
        if (null === $name) {
            return $_SERVER;
        }
        
        return (isset($_SERVER[$name])) ? $_SERVER[$name] : $default;
    }

    public function isCli ()
    {
        if ('CLI' == $this->getMethod()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine if the request has been dispatched
     *
     * @return boolean
     */
    public function isDispatched ()
    {
        return $this->_dispatched;
    }

    /**
     * Was the request made by GET?
     *
     * @return boolean
     */
    public function isGet ()
    {
        if ('GET' == $this->getMethod()) {
            return true;
        }
        
        return false;
    }

    /**
     * Was the request made by HEAD?
     *
     * @return boolean
     */
    public function isHead ()
    {
        if ('HEAD' == $this->getMethod()) {
            return true;
        }
        
        return false;
    }

    /**
     * Was the request made by OPTIONS?
     *
     * @return boolean
     */
    public function isOptions ()
    {
        if ('OPTIONS' == $this->getMethod()) {
            return true;
        }
        
        return false;
    }

    /**
     * Was the request made by POST?
     *
     * @return boolean
     */
    public function isPost ()
    {
        if ('POST' == $this->getMethod()) {
            return true;
        }
        
        return false;
    }

    /**
     * Was the request made by PUT?
     *
     * @return boolean
     */
    public function isPut ()
    {
        if ('PUT' == $this->getMethod()) {
            return true;
        }
        
        return false;
    }

    /**
     * Was the request made by DELETE?
     *
     * @return boolean
     */
    public function isDelete ()
    {
        if ('DELETE' == $this->getMethod()) {
            return true;
        }
        
        return false;
    }

    /**
     * Is the request a Javascript XMLHttpRequest?
     *
     * Should work with Prototype/Script.aculo.us, possibly others.
     *
     * @return boolean
     */
    public function isXmlHttpRequest ()
    {
        return (strcasecmp($this->getServer('HTTP_X_REQUESTED_WITH'), 'XMLHttpRequest') == 0 ? true : false);
    }

    /**
     * Determine if the request has been routed
     *
     * @return boolean
     */
    public function isRouted ()
    {
        return $this->_routed;
    }

    /**
     * Set the action name
     *
     * @param string $value            
     *
     * @return Yaf_Request_Abstract
     */
    public function setActionName ($action)
    {
        if (! is_string($action)) {
            throw new Yaf_Exception('Expect a string action name');
        }
        $this->_action = $action;
        if (null === $action) {
            $this->setParam('action', $action);
        }
        
        return $this;
    }

    /**
     * Set the controller name to use
     *
     * @param string $value            
     *
     * @return Yaf_Request_Abstract
     */
    public function setControllerName ($controller)
    {
        if (! is_string($controller)) {
            throw new Yaf_Exception('Expect a string controller name');
        }
        $this->_controller = $controller;
        
        return $this;
    }

    public function setDispatched ($dispatched = true)
    {
        $this->_dispatched = $dispatched;
    }

    /**
     * Set the module name to use
     *
     * @param string $value            
     *
     * @return Yaf_Request_Abstract
     */
    public function setModuleName ($module)
    {
        if (! is_string($module)) {
            throw new Yaf_Exception('Expect a string module name');
        }
        $this->_module = $module;
        
        return $this;
    }

    /**
     * Set an action parameter
     *
     * A $value of null will unset the $key if it exists
     *
     * @param string $key            
     * @param mixed $value            
     *
     * @return Yaf_Request_Abstract
     */
    public function setParam ($name, $value = null)
    {
        if (is_array($name)) {
            $this->_params = $this->_params + (array) $name;
            $_REQUEST = $_REQUEST + (array) $name;
            
            /*
             * foreach ($name as $key => $value) { if (null === $value) { unset($this->_params[$key]); } }
             */
        } else {
            $name = (string) $name;
            
            /*
             * if ((null === $value) && isset($this->_params[$name])) { unset($this->_params[$name]); } elseif (null !== $value) { $this->_params[$name] = $value; }
             */
            $this->_params[$name] = $value;
            $_REQUEST[$name] = $value;
        }
        
        return $this;
    }

    /**
     * Unset all user parameters
     *
     * @return Yaf_Request_Abstract
     */
    public function clearParams ()
    {
        $this->_params = array();
        
        return $this;
    }

    public function setRequestUri ($requestUri = null)
    {
        $this->_requestUri = $requestUri;
        
        return $this;
    }

    /**
     * Set flag indicating whether or not request has been dispatched
     *
     * @param boolean $flag            
     *
     * @return Yaf_Request_Abstract
     */
    public function setRouted ($flag = true)
    {
        $this->_routed = $flag ? true : false;
        
        return $this;
    }
}
