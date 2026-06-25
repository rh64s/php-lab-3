<?php

namespace Middlewares;

use Src\Auth\Auth;
use Src\Request;
use Src\View;

class OnlyAdministratorMiddleware
{
    public function handle(Request $request)
    {
        $role_id = (int) Auth::user()->role_id;
        if ($role_id !== 1) {
            return (new View)->render('site.errors.403');
        }
    }

}