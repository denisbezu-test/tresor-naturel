<?php


class Dispatcher
{
    private $defaultController = 'HomeController';

    private $errorController = 'ErrorController';

    public function dispatch()
    {
        $controller = $_GET['controller'];
        $dirname = dirname(__FILE__) . '/../controllers/';

        if (is_null($controller)) {
            $controllerName = $this->defaultController;
        } else {
            $controllerName = ucfirst(strtolower($controller)) . 'Controller';
        }

        include_once $dirname . $controllerName . '.php';

        if (!class_exists($controllerName)) {
            $controllerName = $this->errorController;
            include_once $dirname . $controllerName . '.php';
        }

        /** @var Controller $controllerObj */
        $controllerObj = new $controllerName;
        $controllerObj->run();
    }
}