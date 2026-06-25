<?php

namespace Src;

use Error;
use Src\Traits\SingletonTrait;

class Route
{
    use SingletonTrait;
    private static array $routes = ['GET' => [], 'POST' => []];
    private static array $dynamicRoutes = ['GET' => [], 'POST' => []];
    private static string $prefix = '';
    private string $currentRoute;
    private string $currentMethod;

    public function setPrefix(string $value = ''): self
    {
        self::$prefix = $value;
        return $this;
    }

    public static function add(string $method, string $route, array $action): self
    {
        if (preg_match('/\{(\w+)\}/', $route, $matches)) {
            $pattern = '#^' . preg_replace('/\{(\w+)\}/', '([^/]+)', $route) . '$#';
            self::$dynamicRoutes[$method][$pattern] = [$action, 'params' => $matches[1] ?? null];
        } else {
            self::$routes[$method][$route] = $action;
        }
        self::single()->currentRoute = $route;
        self::single()->currentMethod = $method;

        return self::single();
    }

    public static function get(string $route, array $action): self {
        self::single()->add('GET', $route, $action);
        return self::single();
    }
    public static function post(string $route, array $action): self {
        self::single()->add('POST', $route, $action);
        return self::single();
    }
    public function start(): void
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $path = explode('?', $_SERVER['REQUEST_URI'])[0];
        $path = substr($path, strlen(self::$prefix) + 1);
        if (str_ends_with($path, '/') === true) {
            $path = substr($path, 0, strlen($path) - 1);
        }
        $class = null;
        $action = null;
        $found = false;
//        $params = [];
        $request = new Request();
        // сначала статические
        if (array_key_exists($path, self::$routes[$httpMethod])) {
            $class = self::$routes[$httpMethod][$path][0];
            $action = self::$routes[$httpMethod][$path][1];
            $found = true;
        } else {
            //тут перебор динамики
            foreach (self::$dynamicRoutes[$httpMethod] as $pattern => $controllerAction) {
                if (preg_match($pattern, $path, $matches)) {
                    $class = $controllerAction[0][0];

                    $action = $controllerAction[0][1];
//                    $params = [$controllerAction['params'] => array_slice($matches, 1)[0]];
                    $request->set($controllerAction['params'], array_slice($matches, 1)[0]);
                    $found = true;
                    break;
                }
            }
        }

        if (!$found) {
            throw new Error('This path does not exist or method not allowed', 404);
        }

        if (!class_exists($class)) {
            throw new Error('This class does not exist', 500);
        }

        if (!method_exists($class, $action)) {
            throw new Error('This method does not exist', 500);
        }
        $vars = [Middleware::single()->go($httpMethod, $path, $request)];

        call_user_func([new $class, $action], ...$vars);
    }

    public function redirect(string $url): void
    {
        header('Location: ' . $this->getUrl($url));
    }

    public function getUrl(string $url): string
    {
        return self::$prefix . $url;
    }

    public function middlewares(...$middlewares): self
    {
        Middleware::single()->add($this->currentMethod, $this->currentRoute, $middlewares, preg_match('/\{(\w+)\}/', $this->currentRoute));
        return $this;
    }
}