<?php

namespace Controllers;

use Models\Theme;
use Models\User;
use Src\Request;
use Src\View;

class AdminController
{
    public function index(): string
    {
        return new View('site.admin.dashboard', ['themes' => Theme::all(), 'users' => User::all()]);
    }

    public function createTheme(Request $request): string
    {
        app()->route->redirect('/admin/dashboard');
        return $this->index();
    }

    public function pagesIndex(): string
    {
        $pages = [];
        $routeFile = __DIR__ . '/../../routes/created_pages.php';

        if (file_exists($routeFile)) {
            $content = file_get_contents($routeFile);
            $lines = explode(';', $content);

            foreach ($lines as $line) {
                if (preg_match("/Route::get\('pages\/([^']+)'/", $line, $matches)) {
                    $slug = $matches[1];
                    $viewPath = __DIR__ . '/../../views/site/created/' . $slug . '.php';
                    $pages[] = [
                        'slug' => $slug,
                        'exists' => file_exists($viewPath)
                    ];
                }
            }
        }
        return new View('site.admin.pages.index', ['pages' => $pages]);
    }

    public function pagesCreate(Request $request): string
    {
        if ($request->method === 'POST') {
            $slug = $_POST['slug'];
            $content = $_POST['content'];
            
            $routeFile = __DIR__ . '/../../routes/created_pages.php';
            if (file_exists($routeFile)) {
                $routesContent = file_get_contents($routeFile);
                if (strpos($routesContent, "'pages/{$slug}'") !== false) {
                    throw new \Exception('Page with this slug already exists');
                }
            }
            
            $viewPath = __DIR__ . '/../../views/site/created/' . $slug . '.php';
            if (file_exists($viewPath)) {
                throw new \Exception('Page file already exists');
            }
            
            file_put_contents($viewPath, $content);
            
            $routeLine = "\nRoute::get('pages/{$slug}', [Controllers\PageController::class, 'index']);";
            file_put_contents($routeFile, $routeLine, FILE_APPEND);
            
            app()->route->redirect('/admin/pages');
        }
        
        return new View('site.admin.pages.create');
    }

    public function pagesEdit(Request $request): string
    {
        $slug = $request->get('slug');
        $viewPath = __DIR__ . '/../../views/site/created/' . $slug . '.php';

        if (!file_exists($viewPath)) {
            throw new \Exception('Page not found');
        }
        
        if ($request->method === 'POST') {
            $content = $_POST['editable'];
            
            file_put_contents($viewPath, $content);
            
            app()->route->redirect('/admin/pages');
        }
        
        $editable = file_get_contents($viewPath);
        return new View('site.admin.pages.edit', [
            'slug' => $slug,
            'editable' => $editable
        ]);
    }

    public function pagesDelete(Request $request): string
    {

        $slug = $request->get('slug');
        $viewPath = __DIR__ . '/../../views/site/created/' . $slug . '.php';
        $routeFile = __DIR__ . '/../../routes/created_pages.php';
        
        if (file_exists($viewPath)) {
            unlink($viewPath);
        }
        
        if (file_exists($routeFile)) {
            $content = file_get_contents($routeFile);
            $lines = explode(';', $content);
            $newLines = [];
            
            foreach ($lines as $line) {
                if (strpos($line, "'pages/{$slug}'") === false && trim($line) !== '') {
                    $newLines[] = $line;
                }
            }
            
            file_put_contents($routeFile, implode(';', $newLines) . ';');
        }
        
        app()->route->redirect('/admin/pages');
        return $this->pagesIndex();
    }

    // USERS

    public function usersIndex(): string
    {
        return new View('site.admin.users.index', ['users' => User::all()]);
    }
}