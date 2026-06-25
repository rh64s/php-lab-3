<?php

namespace Controllers;

use Src\Auth\Auth;
use Src\DB\Operators;
use Src\Request;
use Src\Validator\Validator;
use Src\View;
use Models\User;

class AuthController
{
    public function login(Request $request): string
    {
        if ($request->method === 'POST') {
            $message = '';
            if (Auth::attempt($request->all())) {
                $message = 'Успешно';
            } else {
                $message = 'Не успешно(';
                return new View('site.auth.login', ['message' => $message]);
            }
            app()->route->redirect('/');
            return new View('site.hello', ['message' => $message]);
        }

        return new View('site.auth.login');
    }

    public function register(Request $request): string
    {
        if ($request->method === 'POST') {
            $validation = new Validator($request->all(), [
                'login' => ['required', 'unique:users,login', 'max:255', 'regex:/^[a-zA-Z0-9_-]+$/'],
                'password' => ['required', 'max:255', 'min:8'],
            ],
            [
                'login' => [
                    'required' => 'Введите логин',
                    'unique' => 'Такой логин уже существует',
                    'max' => "Поле логина должно быть не больше 255 символов",
                    'regex' => 'Только латиница, цифры, дефис "-", нижнее подчеркивание "_"'
                ],
                'password' => [
                    'required' => 'Введите пароль',
                    'max' => 'Поле пароля должно быть не больше 255 символов',
                    'min' => 'Поле пароля должно быть не меньше 8 символов'
                ]
            ]);

            if(!$validation->success()) {
                return new View('site.auth.register', ['validation' => $validation->errors()]);
            }

            return new View('site.auth.login', ["message" => 'Теперь, войдите в систему']);
        }

        return new View('site.auth.register');
    }

    public function logout(Request $request): string
    {
        Auth::logout();
        app()->route->redirect('/login');
        return new View('site.auth.login');
    }
}