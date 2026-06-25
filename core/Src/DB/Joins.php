<?php

namespace Src\DB;

enum Joins: string
{
    case LEFT = 'LEFT';
    case RIGHT = 'RIGHT';
    case INNER = 'INNER';
    public function get(): string { return $this->value; }
}