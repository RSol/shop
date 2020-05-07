<?php


namespace core;


use Exception;

class App
{
    /**
     * @var self
     */
    private static $instance;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var View
     */
    private $view;

    /**
     * App constructor.
     * @param $config
     * @throws Exception
     */
    private function __construct($config)
    {
        $routParams = Helper::getValue($config, 'router', []);
        $this->setRouter($routParams);

        $viewParams = Helper::getValue($config, 'view', [
            'path' => dirname(__DIR__) . '/app/views',
        ]);
        $this->setView($viewParams);
    }

    /**
     * @param array $config
     * @return App
     */
    public static function getInstance($config = [])
    {
        if (static::$instance === null) {
            static::$instance = new static($config);
        }

        return static::$instance;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        if (!$this->request) {
            $this->request = new Request();
        }
        return $this->request;
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @param Router|array $router
     */
    public function setRouter($router)
    {
        if (is_array($router)) {
            $router = Router::getInstance($router);
        }
        $this->router = $router;
    }

    /**
     * @return View
     */
    public function getView(): View
    {
        return $this->view;
    }

    /**
     * @param View|array $view
     * @throws Exception
     */
    public function setView($view)
    {
        if (is_array($view)) {
            $view = new View($view);
        }
        $this->view = $view;
    }

    /**
     * @throws Exception
     */
    public function run()
    {
        $path = $this->getRequest()->getRoute();
        $route = $this->getRouter()->getRoute($path);


        $controllerId = $route->getControllerName();
        $actionId = $route->getActionName();

        $controller = BaseController::getInstance($controllerId);

        if (!method_exists($controller, $actionId)) {
            throw new Exception('Action not found');
        }

        return $controller->$actionId();
    }
}
