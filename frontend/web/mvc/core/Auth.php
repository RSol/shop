<?php


namespace core;


class Auth
{
    /**
     * @var self
     */
    private static $instance;

    /**
     * @var Session
     */
    private $session;

    public $authKey = 'auth_key';

    private function __construct()
    {
        $this->session = Session::getInstance();
    }

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    public function set($value)
    {
        $this->getSession()->set($this->authKey, $value);
    }

    public function get()
    {
        return $this->getSession()->get($this->authKey);
    }

    public function has()
    {
        return (bool) $this->get();
    }

    public function reset()
    {
        $this->getSession()->reset($this->authKey);
    }

}
