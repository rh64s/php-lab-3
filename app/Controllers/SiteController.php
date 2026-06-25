<?php

namespace Controllers;

use Src\DB\QueryBuilder;
use Src\View;

class SiteController
{
    public function index(): string
    {
        return new View('site.hello');
    }
}