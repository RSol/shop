<?php


namespace core;


class Route
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $path;

    public function __construct($path, $config)
    {
        $this->setPath($path);
        $this->setConfig($config);
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
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        [$controller, $action] = explode('/', $this->getPath());
        return Helper::getValue($this->getConfig(), 'controller', $controller);
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        [$controller, $action] = explode('/', $this->getPath());
        return Helper::getValue($this->getConfig(), 'action', $action);
    }
}
