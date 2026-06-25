<?php

namespace Src\DB;

enum Operators: string
{
    case EQUAL = '=';
    case NOT_EQUAL = '!=';
    case LESS = '<';
    case LESS_OR_EQUAL = '<=';
    case MORE = '>';
    case MORE_OR_EQUAL = '>=';
    case IS_NULL = 'IS NULL';
    case IS_NOT_NULL = 'IS NOT NULL';


    public function get(): string
    {
        return $this->value;
    }     
}