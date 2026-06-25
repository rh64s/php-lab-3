<?php

namespace Controllers;

use Src\Request;
use Src\View;

class PageController
{
    public function index(Request $request):string
    {
        $path = explode('?', $_SERVER['REQUEST_URI'])[0];
        $parts = explode('/', trim($path, '/'));
        $slug = end($parts);
        
        $viewPath = __DIR__ . '/../../views/site/created/' . $slug . '.php';
        if (!file_exists($viewPath)) {
            throw new \Exception('Page not found', 404);
        }

        return new View('site.created.' . $slug);
    }
}