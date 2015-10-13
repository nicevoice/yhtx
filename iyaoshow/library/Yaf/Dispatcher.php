<?php

/**
 * Yaf Dispatcher
 */
class Yaf_Dispatcher
{

    /**
     * Singleton instance
     *
     * @var Yaf_Dispatcher
     */
    protected static $_instance = null;

    /**
     * Instance of Yaf_Router_Interface
     *
     * @var Yaf_Router
     */
    protected $_router = null;

    /**
     * View object
     *
     * @var Yaf_View_Interface
     */
    protected $_view = null;

    /**
     * Instance of Yaf_Request_Abstract
     *
     * @var Yaf_Request_Abstract
     */
    protected $_request = null;

    /**
     * holds the references to the plugins
     *
     * @var array
     */
    protected $_plugins = array();

    /**
     * Whether or not to enable view.
     *
     * @var boolean
     */
    protected $_auto_render = true;

    /**
     * Whether or not to return the response prior to rendering output while in
     * {@link dispatch()}; default is to send headers and render output.
     *
     * @var boolean
     */
    protected $_returnResponse = false;

    /**
     * Constructor
     *
     * Instantiate using {@link getInstance()}; dispatcher is a singleton
     * object.
     *
     * @return void
     */
    protected function __construct ()
    {}

    /**
     * Singleton instance
     *
     * @return Yaf_Dispatcher
     */
    public static function getInstance ()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->_router = new Yaf_Router();
        }

        return self::$_instance;
    }

    /**
     * 这个是核心方法
     * Dispatch an HTTP request to a controller/action.
     *
     * @param Yaf_Request_Abstract|null $request
     *
     * @return void Yaf_Response_Abstract
     */
    public function dispatch ()
    {
        $request = $this->getRequest();
        if (! ($request instanceof Yaf_Request_Abstract)) {
            throw new Yaf_Exception('Expect a Yaf_Request_Abstract instance');
        }
        if ($request instanceof Yaf_Request_Http) {
            $response = new Yaf_Response_Http();
        } elseif ($request instanceof Yaf_Request_Cli) {
            $response = new Yaf_Response_Cli();
        }
        // 选择路由
        $router = $this->getRouter();
        foreach ($this->_plugins as $plugin) {
            $plugin->routerStartup($request, $response);
        }
        $router->route($request);
        $this->_fixDefault($request);
        foreach ($this->_plugins as $plugin) {
            $plugin->routerShutdown($request, $response);
        }
        // 执行Action
        try {
            $view = $this->initView();
            foreach ($this->_plugins as $plugin) {
                $plugin->dispatchLoopStartup($request, $response);
            }
            $nested = Yaf_G::getConf('forward_limit');
            $nested = empty($nested) ? 5 : $nested;
            do {
                foreach ($this->_plugins as $plugin) {
                    $plugin->preDispatch($request, $response, $view);
                }
                $this->handle($request, $response, $view);
                $this->_fixDefault($request);
                foreach ($this->_plugins as $plugin) {
                    $plugin->postDispatch($request, $response);
                }
                $nested --;
            } while (! $request->isDispatched() && $nested > 0);
            foreach ($this->_plugins as $plugin) {
                $plugin->dispatchLoopShutdown($request, $response);
            }
        } catch (Yaf_BreakException $oExp) { //
        } catch (Exception $oExp) {
            if (Yaf_G::isDebug() || $request->getMethod() == 'CLI') {
                if ($request->getMethod() == 'CLI') {
                    Yaf_Logger::getInstance()->error(Yaf_G::parseException($oExp));
                    echo Yaf_G::parseException($oExp);
                } else {
                    echo "<pre>";
                    echo Yaf_G::parseException($oExp);
                    echo "</pre>";
                }
            } else {
                $response->setResponseCode(404);
                $view->display('404.phtml');
            }
        }

        if ($nested == 0 && ! $request->isDispatched()) {
            throw new Yaf_Exception('The max dispatch nesting ' . Yaf_G::getConf('forward_limit') . ' was reached');
        }

        if ($this->returnResponse() == false) {
            $response->response();
        }
        return $response;
    }

    /**
     * 设置是否自动render
     *
     * @param unknown $flag
     */
    public function autoRender ($flag = null)
    {
        if (is_null($flag)) {
            return $this->_auto_render;
        } else {
            $this->_auto_render = $flag;
        }
    }

    /**
     * returns the application
     *
     * @return Yaf_Application
     */
    public function getApplication ()
    {
        return Yaf_Application::app();
    }

    /**
     * Return the request object.
     *
     * @return null Yaf_Request_Abstract
     */
    public function getRequest ()
    {
        return $this->_request;
    }

    /**
     * Set the request object.
     *
     * @param Yaf_Request_Abstract $request
     *
     * @return Yaf_Dispatcher
     */
    public function setRequest (Yaf_Request_Abstract $request)
    {
        $this->_request = $request;

        return $this;
    }

    /**
     * Return the router object.
     *
     * @return Yaf_Router
     */
    public function getRouter ()
    {
        return $this->_router;
    }

    /**
     * 初始化View
     *
     * @param string $templates_dir
     * @param unknown $options
     * @return Yaf_View_Interface
     */
    public function initView ($templates_dir = null, $options = array())
    {
        if ($this->_view == null) {
            if (empty($templates_dir)) {
                $templates_dir = Yaf_G::getViewPath();
            }
            $this->_view = new Yaf_View_Simple($templates_dir, $options);
        }

        return $this->_view;
    }

    /**
     * Register a plugin.
     *
     * @param Yaf_Plugin $plugin
     *
     * @return Yaf_Dispatcher
     */
    public function registerPlugin (Yaf_Plugin $plugin)
    {
        $this->_plugins[] = $plugin;

        return $this;
    }

    /**
     * Set whether {@link dispatch()} should return the response without first
     * rendering output.
     * By default, output is rendered and dispatch() returns
     * nothing.
     *
     * @param boolean $flag
     *
     * @return boolean Yaf_Dispatcher as a setter,
     *         returns object; as a getter, returns boolean
     */
    public function returnResponse ($flag = null)
    {
        if (true === $flag) {
            $this->_returnResponse = true;

            return $this;
        } elseif (false === $flag) {
            $this->_returnResponse = false;

            return $this;
        }

        return $this->_returnResponse;
    }

    public function setErrorHandler ($callback, $error_types = E_ALL)
    {
        set_error_handler($callback, $error_types);
    }

    /**
     * Set the view object.
     *
     * @param Yaf_View_Interface $view
     *
     * @return Yaf_Dispatcher
     */
    public function setView (Yaf_View_Interface $view)
    {
        $this->_view = $view;

        return $this;
    }

    private function handle (Yaf_Request_Abstract $request, Yaf_Response_Abstract $response, Yaf_View_Interface $view)
    {
        $request->setDispatched(true);
        $app = $this->getApplication();
        $module = $request->getModuleName();
        if (empty($module)) {
            throw new Yaf_Exception('Unexcepted an empty module name');
            return false;
        }
        $controllerName = $request->getControllerName();
        if (empty($controllerName)) {
            throw new Yaf_Exception('Unexcepted an empty controller name');
            return false;
        }
        $className = $this->getController($module, $controllerName);
        if (! $className) {
            return false;
        }
        $controller = new $className($request, $response, $view);
        if (! ($controller instanceof Yaf_Controller)) {
            throw new Yaf_Exception('Controller must be an instance of Yaf_Controller');
            return false;
        }
        $action = $request->getActionName();
        $actionMethod = $action . 'Action';
        if (! method_exists($controller, $actionMethod)) {
            throw new Yaf_Exception($className . '::' . $actionMethod . ' not exists!');
            return false;
        }
        $ret = call_user_func(array(
            $controller,
            'actionBefore'
        ));
        if ($ret === false) {
            return false;
        }
        $ret = call_user_func(array(
            $controller,
            $actionMethod
        ));
        if ($ret === false) {
            return false;
        }
        $ret = call_user_func(array(
            $controller,
            'actionAfter'
        ));
        if ($ret === false) {
            return false;
        }

        if ($this->_auto_render == true) {
            $response->setBody($controller->render($action));
        }
        $controller = null;
    }

    private function getActionParams ($className, $action)
    {
        $funcRef = new ReflectionMethod($className, $action);
        $paramsRef = $funcRef->getParameters();

        return $paramsRef;
    }

    private function getController ($module, $controller)
    {
        $classname = Yaf_G::YAF_CONTROLLER_DIRECTORY_NAME . '_' . ucfirst($module) . '_' . ucfirst($controller);
        return $classname;
    }

    private function _fixDefault (Yaf_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        if (empty($module) || ! is_string($module)) {
            $request->setModuleName(Yaf_G::YAF_ROUTER_DEFAULT_MODULE);
        } else {
            $request->setModuleName(ucfirst($module));
        }
        $controller = $request->getControllerName();
        if (empty($controller) || ! is_string($controller)) {
            $request->setControllerName(Yaf_G::YAF_ROUTER_DEFAULT_CONTROLLER);
        } else {
            $request->setControllerName($this->_formatName($controller));
        }
        $action = $request->getActionName();
        if (empty($action) || ! is_string($action)) {
            $request->setActionName(Yaf_G::YAF_ROUTER_DEFAULT_ACTION);
        } else {
            $request->setActionName(strtolower($action));
        }
    }

    private function _formatName ($unformatted)
    {
        // we have namespace
        $segments = explode('\\', $unformatted);
        if ($segments != null) {
            foreach ($segments as $key => $segment) {
                $segment = preg_replace('/[^a-z0-9 ]/', '', strtolower($segment));
                $segments[$key] = str_replace(' ', '', ucwords($segment));
            }

            return implode('\\', $segments);
        }
        // we have _
        $segments = explode('_', $unformatted);
        if ($segments != null) {
            foreach ($segments as $key => $segment) {
                $segment = preg_replace('/[^a-z0-9 ]/', '', strtolower($segment));
                $segments[$key] = str_replace(' ', '', ucwords($segment));
            }

            return implode('_', $segments);
        }
    }
}
