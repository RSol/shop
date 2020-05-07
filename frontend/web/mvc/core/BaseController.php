<?php


namespace core;


use Exception;

class BaseController
{
    /**
     * @var string
     */
    private static $controllerNamespace = 'app\\controllers\\';

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    public $layout = 'main';

    /**
     * @var string
     */
    public $viewPath;

    /**
     * @param $controllerId
     * @return mixed|static
     * @throws Exception
     */
    public static function getInstance($controllerId)
    {
        $fullName = self::getControllerNamespace() . ucfirst(strtolower($controllerId). 'Controller');
        if (!class_exists($fullName)) {
            throw new Exception('Controller not found');
        }

        $controller = new $fullName;
        if ($controller instanceof self) {
            $controller->setId(strtolower($controllerId));
            return $controller;
        }

        throw new Exception('Controller not found');
    }

    public function getView()
    {
        return App::getInstance()->getView();
    }

    public function render($view, $params = [])
    {
        $view = "{$this->getId()}/{$view}";
        $content = App::getInstance()->getView()->render($view, $params);
        if ($this->layout) {
            $layout = "layouts/{$this->layout}";
            $content = App::getInstance()->getView()->render($layout, [
                'content' => $content,
            ]);
        }
        return $content;
    }

    /**
     * @return string
     */
    public static function getControllerNamespace(): string
    {
        return self::$controllerNamespace;
    }

    /**
     * @param string $controllerNamespace
     */
    public static function setControllerNamespace(string $controllerNamespace): void
    {
        self::$controllerNamespace = $controllerNamespace;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return App::getInstance()->getRequest();
    }

    public function redirect($url)
    {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: {$url}");
        exit();
    }
}
