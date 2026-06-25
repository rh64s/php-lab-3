<?php

use Src\Route;
Route::get('', [Controllers\SiteController::class, 'index']);
Route::get('login', [Controllers\AuthController::class, 'login']);
Route::post('login', [Controllers\AuthController::class, 'login']);
Route::get('logout', [Controllers\AuthController::class, 'logout'])->middlewares('auth');

Route::get('register', [Controllers\AuthController::class, 'register']);
Route::post('register', [Controllers\AuthController::class, 'register']);

Route::get('themes', [Controllers\ThemeController::class, 'index'])->middlewares('int:id');
//Route::get('themes', [Controllers\ThemeController::class, 'show']);
Route::get('themes/{id}', [Controllers\ThemeController::class, 'show'])->middlewares('int:id');

// Admin page management
Route::get('admin/dashboard', [Controllers\AdminController::class, 'index'])
    ->middlewares('auth', 'admin');

Route::get('admin/pages', [Controllers\AdminController::class, 'pagesIndex'])
    ->middlewares('auth', 'admin');

Route::get('admin/pages/create', [Controllers\AdminController::class, 'pagesCreate'])
    ->middlewares('auth', 'admin');
Route::post('admin/pages/create', [Controllers\AdminController::class, 'pagesCreate'])
    ->middlewares('auth', 'admin');

Route::get('admin/pages/{slug}/edit', [Controllers\AdminController::class, 'pagesEdit'])
    ->middlewares('auth', 'admin');
Route::post('admin/pages/{slug}/edit', [Controllers\AdminController::class, 'pagesEdit'])
    ->middlewares('auth', 'admin');

Route::post('admin/pages/{slug}/delete', [Controllers\AdminController::class, 'pagesDelete'])
    ->middlewares('auth', 'admin');

// Include created pages routes
require_once __DIR__ . '/created_pages.php';