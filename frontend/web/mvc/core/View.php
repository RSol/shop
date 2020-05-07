<?php


namespace core;


use Exception;

class View
{
    /**
     * @var string
     */
    private $basePath;

    public function __construct($config)
    {
        if (!$path = Helper::getValue($config, 'path')) {
            throw new Exception('Wrong config');
        }

        $this->setBasePath($path);
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * @param string $basePath
     */
    public function setBasePath(string $basePath)
    {
        $this->basePath = $basePath;
    }

    public function render($view, $params = [])
    {
        if (is_array($params)) {
            extract($params, EXTR_OVERWRITE);
        }
        ob_start();

        include $this->getBasePath() . DIRECTORY_SEPARATOR . $view . '.php';

        $out = ob_get_contents();
        ob_end_clean();

        return $out;
    }
}
