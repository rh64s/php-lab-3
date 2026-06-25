<?php


namespace Middlewares;

use Exception;
use Src\Request;
use Src\Session;

class OnlyNumInParameter
{
    public function handle(Request $request, $field): void
    {
        if(!isset($request->all()[$field]) || ((string) gettype((int) $request->get($field))) == 'NULL') {
            return;
        }
        if(!is_numeric($request->get($field))) {
            throw new Exception("parameter must be numeric");
        }
    }
}