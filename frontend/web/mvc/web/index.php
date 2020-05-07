<?php

function autoloader($class) {
    $parts = explode('\\', $class);
    $path = implode('/', $parts);

    $fileName = dirname(__DIR__) . DIRECTORY_SEPARATOR . $path . '.php';
    if (!file_exists($fileName)) {
        throw new Exception();
    }

    require $fileName;
}
spl_autoload_register('autoloader');

function exceptionHandler(Throwable $e)
{
    echo "<h1>{$e->getMessage()}</h1>";
    echo "<h4>{$e->getFile()}:{$e->getLine()}</h4>";
}
set_exception_handler("exceptionHandler");


$config = require dirname(__DIR__) . '/app/config/main.php';

echo core\App::getInstance($config)->run();
