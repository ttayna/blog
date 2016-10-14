<?php
/**
 * Created by PhpStorm.
 * User: Tayna
 * Date: 10.10.2016
 * Time: 23:23
 */

namespace kernel;

/**
 * Class Loader
 * @package kernel
 */
class Loader
{
    CONST ROUTING_FILE = '/../../config/routing.json';

    /** @var array */
    private $routes = [];

    /**
     * Loader constructor.
     * @throws \Exception
     */
    function __construct()
    {
        $this->routes = json_decode(file_get_contents(__DIR__ . self::ROUTING_FILE), true);

        if (!$this->routes) {
            throw new \Exception('Cannot read routing file.');
        }
    }

    /**
     * Parsing of url
     * @return mixed|null
     */
    private function getRoute()
    {
        $uri = trim(str_replace('/app.php', '', $_SERVER['REQUEST_URI']));
        $uri = explode('?', $uri)[0];
        $uriParts = explode('/', trim($uri));

        foreach ($this->routes as $key => $route) {
            $routeParts = explode('/', $route['path']);
            if (
                count($routeParts) == count($uriParts) &&
                count($routeParts) == 2 &&
                $routeParts[1] == $uriParts[1] &&
                in_array($_SERVER['REQUEST_METHOD'], $route['method'])
            ) {
                return $route;
            } elseif (
                count($routeParts) == count($uriParts) &&
                $routeParts[1] == $uriParts[1] &&
                in_array($_SERVER['REQUEST_METHOD'], $route['method'])
            ) {
                $route['id'] = (int)$uriParts[2];
                $route['path'] = '/' . $uriParts[1];
                return $route;
            }
        }

        return null;
    }

    /**
     * Main function for this app
     */
    public function run()
    {
        if (!$route = $this->getRoute()) {
            $controller = new Controller();
            $controller->error404();
            exit();
        }

        $controllerAndAction = explode(':', $route['controller']);
        if (!$controllerAndAction
            || count($controllerAndAction) !== 2
            || !class_exists($controllerAndAction[0])
            || !method_exists(new $controllerAndAction[0], $controllerAndAction[1])
        ) {
            die("Fatal error: routing");
        }

        call_user_func(
            [new $controllerAndAction[0](!empty($route['view']) ? $route['view'] : null), $controllerAndAction[1]],
            !empty($route['id']) ? $route['id'] : []
        );
    }
}