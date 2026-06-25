<?php

namespace Controllers;

use DebugTools\Debug;
use Models\Questions;
use Models\Theme;
use Src\Request;
use Src\View;

class ThemeController
{
    public function index(Request $request): string
    {
        return new View('site.themes.index' , ['themes' => Theme::all()]);
    }

    public function show(Request $request): string
    {
        if (Theme::find((int) $request->get('id'))) return new View('site.themes.show' , ['theme' => Theme::find($request->get('id')), 'questions' => Questions::where('theme_id' , $request->get('id'))->get()]);
        return new View('site.errors.notfound');
    }
}