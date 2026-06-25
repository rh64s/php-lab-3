<?php

namespace Src;

use Error;
use Src\Auth\Auth;
use Src\DB\Manager;
use Src\Settings;

class Application
{
    private Settings $settings;
    private Route $route;
    private Manager $dbManager;
    private Auth $auth;

    public function __construct(Settings $settings)
    {
        //Привязываем класс со всеми настройками приложения
        $this->settings = $settings;
        //Привязываем класс маршрутизации с установкой префикса
        $this->route = Route::single()->setPrefix($this->settings->getRootPath());
        // $this->route = new Route($this->settings->getRootPath());
        // Route::single()->setPrefix($this->settings->getRootPath());

        //Создаем класс для аутентификации на основе настроек приложения
        $this->auth = new $this->settings->app['auth'];

        //Настройка для работы с базой данных
        $this->dbRun();
        //Инициализация класса пользователя на основе настроек приложения
        $this->auth::init(new $this->settings->app['identity']);
    }

    public function __get($key)
    {
        switch ($key) {
            case 'settings':
                return $this->settings;
            case 'route':
                return $this->route;
            case 'auth':
                return $this->auth;
        }
        throw new Error('Accessing a non-existent property');
    }

    private function dbRun()
    {
        $config = require __DIR__ . '/..' . DIR_CONFIG . '/db.php';
        Manager::single()->init($config);
    }

    public function run(): void
    {
        //Запуск маршрутизации
        $this->route->start();
    }

    function getPublic(): string
    {
        return __DIR__ . '/../../public';
    }
}