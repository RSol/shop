<?php


namespace core;


use Exception;

class Router
{
    /**
     * @var self
     */
    private static $instance;

    private $defaultPath = 'site/index';

    /**
     * @var array
     */
    private $config;

    private function __construct($config)
    {
        $this->setConfig($config);
    }

    public static function getInstance($config)
    {
        if (static::$instance === null) {
            static::$instance = new static($config);
        }

        return static::$instance;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    private function getRoutsFromConfig()
    {
        return Helper::getValue($this->config, 'routs', []);
    }

    /**
     * @return Route
     */
    private function getDefaultRoute()
    {
        $path = Helper::getValue($this->config, 'default', $this->defaultPath);
        if ($conf = Helper::getValue($this->getRoutsFromConfig(), $path)) {
            return new Route($path, $conf);
        }

        throw new Exception('Route not detected');
    }

    /**
     * @param $route
     * @return Route
     */
    public function getRoute($route)
    {
        $method = $this->getMethod();
        foreach ($this->getRoutsFromConfig() as $path => $conf) {
            $routeMethod = Helper::getValue($conf, 'method', 'get');
            if ($path === $route && $routeMethod === $method) {
                return new Route($path, $conf);
            }
        }

        return $this->getDefaultRoute();
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return string
     */
    public function getDefaultPath(): string
    {
        return $this->defaultPath;
    }

    /**
     * @param string $defaultPath
     */
    public function setDefaultPath(string $defaultPath)
    {
        $this->defaultPath = $defaultPath;
    }
}
