<?php


namespace core;


class Session
{
    /**
     * @var self
     */
    private static $instance;

    public $flashKey = 'flash_messages';

    private function __construct()
    {
        session_start();
        $this->resetFlash();
    }

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function get($name = null, $default = null)
    {
        return $this->_params($_SESSION, $name, $default);
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function reset($name)
    {
        unset($_SESSION[$name]);
    }

    public function getAllFlash()
    {
        $data = $this->get($this->flashKey);
        return json_decode($data, true);
    }

    public function resetFlash()
    {
        $this->set($this->flashKey, json_encode([]));
    }

    public function setFlash($type, $message)
    {
        $data = $this->getAllFlash();
        if (!array_key_exists($type, $data)) {
            $data[$type] = [];
        }

        $data[$type][] = $message;

        $this->set($this->flashKey, json_encode($data));
    }

    public function getFlash($type)
    {
        $data = $this->getAllFlash();
        return Helper::getValue($data, $type);
    }

    private function _params($params, $name = null, $default = null)
    {
        return $name
            ? Helper::getValue($params, $name, $default)
            : $params;
    }
}
