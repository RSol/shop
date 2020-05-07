<?php

namespace core;

class Request
{
    /**
     * @var string
     */
    private $routParam = 'r';

    /**
     * @return string
     */
    public function getRoutParam(): string
    {
        return $this->routParam;
    }

    /**
     * @param string $routParam
     */
    public function setRoutParam(string $routParam)
    {
        $this->routParam = $routParam;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return Helper::getValue($_GET, $this->routParam);
    }

    public function getParams($name = null, $default = null)
    {
        return $this->_params($_GET, $name, $default);
    }

    public function postParams($name = null, $default = null)
    {
        return $this->_params($_POST, $name, $default);
    }

    public function requestParams($name = null, $default = null)
    {
        return $this->_params($_REQUEST, $name, $default);
    }

    public function serverParams($name = null, $default = null)
    {
        return $this->_params($_SERVER, $name, $default);
    }

    private function _params($params, $name = null, $default = null)
    {
        return $name
            ? Helper::getValue($params, $name, $default)
            : $params;
    }
}
