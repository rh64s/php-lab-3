<?php

namespace Src;

use Src\Traits\SingletonTrait;

class Middleware
{
    //Используем трейт
    use SingletonTrait;

    private static array $middlewareCollector = [
        'GET' => [], 'POST' => []
    ];
    private static array $dynamicMiddlewareCollector = ['GET' => [], 'POST' => []];

    private function checkMiddlewareExists(string $middleware): bool
    {
        $middleware = explode(':', $middleware)[0];
        if (in_array($middleware, array_keys((include __DIR__ . '/../../config/app.php')['routeMiddleware']))) return true;
        return false;
    }

    public function add($httpMethod, string $route, array $middlewares, bool $isDynamic): void
    {
        array_map(function(string $name) {
            if (!$this->checkMiddlewareExists($name)) throw new \Exception("Middleware '" . explode(':', $name)[0] . "' does not exist.");
        }, $middlewares);

        if ($isDynamic) {
            $pattern = '#^' . preg_replace('/\{(\w+)\}/', '([^/]+)', $route) . '$#';
            self::$dynamicMiddlewareCollector[$httpMethod][$pattern] = $middlewares;
        }

        self::$middlewareCollector[$httpMethod] = [$route => $middlewares];
    }

    public function go(string $httpMethod, string $uri, Request $request): Request
    {
        return $this->runMiddlewares($httpMethod, $uri, $this->runAppMiddlewares($request));
    }

    //Запуск всех middlewares для текущего маршрута
    private function runMiddlewares(string $httpMethod, string $uri, Request $request): Request
    {
        //Получаем список всех разрешенных классов middlewares из настроек приложения
        $routeMiddleware = app()->settings->app['routeMiddleware'];
        //Перебираем все middlewares для текущего адреса
        foreach ($this->getMiddlewaresForRoute($httpMethod, $uri) as $middleware) {
            $args = explode(':', $middleware);
            //Создаем объект и вызываем метод handle
            $request = (new $routeMiddleware[$args[0]])->handle($request, $args[1]) ?? $request;
        }
        //Возвращаем итоговый request
        return $request;
    }

    //Запуск всех глобальных middlewares
    private function runAppMiddlewares(Request $request): Request
    {
        //Получаем список всех разрешенных классов middlewares из настроек приложения
        $routeMiddleware = app()->settings->app['routeAppMiddleware'];

        //Перебираем и запускаем их
        foreach ($routeMiddleware as $name => $class) {
            $args = explode(':', $name);
            $request = (new $class)->handle($request, $args[1]) ?? $request;

        }
        return $request;
    }

    //Поиск middlewares по адресу
    private function getMiddlewaresForRoute(string $httpMethod, string $uri): array
    {
        $names = self::$middlewareCollector[$httpMethod][$uri] ?? null;
        if (!empty($names)) {
            return $this->explodeMiddlewares($names);
        }

        foreach (self::$dynamicMiddlewareCollector[$httpMethod] as $pattern => $middlewares) {
            if (preg_match($pattern, $uri)) {
                return $this->explodeMiddlewares($middlewares);
            }
        }
        return [];
    }

    private function explodeMiddlewares(array $names): array
    {
        return array_intersect_ukey(array_combine($names, $names), app()->settings->app['routeMiddleware'],
            function ($first, $second): string|null
            {
                if (explode(':', $first)[0] === $second) {
                    return $first;
                }
                return null;
            }
        );
    }

}
