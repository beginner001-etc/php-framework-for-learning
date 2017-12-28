<?php

/**
 * Application.
 *
 * @author Katsuhiro Ogawa <fivestar@nequal.jp>
 */
abstract class Application
{
    protected $debug = false;
    protected $request;
    protected $response;
    protected $session;
    protected $db_manager;

    /**
     * constructor
     *
     * @param boolean $debug
     */
    public function __construct($debug = false)
    {
        $this->setDebugMode($debug);
        $this->initialize();
        $this->configure();
    }

    /**
     * set Debug Mode
     * 
     * @param boolean $debug
     */
    protected function setDebugMode($debug)
    {
        if ($debug) {
            $this->debug = true;
            ini_set('display_errors', 1);
            error_reporting(-1);
        } else {
            $this->debug = false;
            ini_set('display_errors', 0);
        }
    }

    /**
     * initialize Application
     */
    protected function initialize()
    {
        $this->request    = new Request();
        $this->response   = new Response();
        $this->session    = new Session();
        $this->db_manager = new DbManager();
        $this->router     = new Router($this->registerRoutes());
    }

    /**
     * Application Settings
     */
    protected function configure()
    {
    }

    /**
     * get Project Root Directory
     *
     * @return string absolute Path on File system to Root Directory
     */
    abstract public function getRootDir();

    /**
     * get Routing
     *
     * @return array
     */
    abstract protected function registerRoutes();

    /**
     * determine whether it is in Debug Mode
     *
     * @return boolean
     */
    public function isDebugMode()
    {
        return $this->debug;
    }

    /**
     * get Request Object
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * get Response Object
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * get Session Object
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * get DbManager Object
     *
     * @return DbManager
     */
    public function getDbManager()
    {
        return $this->db_manager;
    }

    /**
     * get Directory Path where Controller file is stored
     *
     * @return string
     */
    public function getControllerDir()
    {
        return $this->getRootDir() . '/controllers';
    }

    /**
     * get Directory Path where View file is stored
     *
     * @return string
     */
    public function getViewDir()
    {
        return $this->getRootDir() . '/views';
    }

    /**
     * get Directory Path where Model file is stored
     *
     * @return stirng
     */
    public function getModelDir()
    {
        return $this->getRootDir() . '/models';
    }

    /**
     * get Path to Document Root
     *
     * @return string
     */
    public function getWebDir()
    {
        return $this->getRootDir() . '/web';
    }

    /**
     * run Application
     *
     * @throws HttpNotFoundException Route not found
     */
    public function run()
    {
        try {
            $params = $this->router->resolve($this->request->getPathInfo());
            if ($params === false) {
                throw new HttpNotFoundException('No route found for ' . $this->request->getPathInfo());
            }

            $controller = $params['controller'];
            $action = $params['action'];

            $this->runAction($controller, $action, $params);
        } catch (HttpNotFoundException $e) {
            $this->render404Page($e);
        } catch (UnauthorizedActionException $e) {
            list($controller, $action) = $this->login_action;
            $this->runAction($controller, $action);
        }

        $this->response->send();
    }

    /**
     * run specified Action
     *
     * @param string $controller_name
     * @param string $action
     * @param array $params
     *
     * @throws HttpNotFoundException Controller can not be specified
     */
    public function runAction($controller_name, $action, $params = array())
    {
        $controller_class = ucfirst($controller_name) . 'Controller';

        $controller = $this->findController($controller_class);
        if ($controller === false) {
            throw new HttpNotFoundException($controller_class . ' controller is not found.');
        }

        $content = $controller->run($action, $params);

        $this->response->setContent($content);
    }

    /**
     * find corresponding Controller Object from specified Controller name
     *
     * @param string $controller_class
     * @return Controller
     */
    protected function findController($controller_class)
    {
        if (!class_exists($controller_class)) {
            $controller_file = $this->getControllerDir() . '/' . $controller_class . '.php';
            if (!is_readable($controller_file)) {
                return false;
            } else {
                require_once $controller_file;

                if (!class_exists($controller_class)) {
                    return false;
                }
            }
        }

        return new $controller_class($this);
    }

    /**
     * Setting to return 404 Error Page
     *
     * @param Exception $e
     */
    protected function render404Page($e)
    {
        $this->response->setStatusCode(404, 'Not Found');
        $message = $this->isDebugMode() ? $e->getMessage() : 'Page not found.';
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

        $this->response->setContent(<<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>404</title>
</head>
<body>
    {$message}
</body>
</html>
EOF
        );
    }
}
