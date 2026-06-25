<?php

namespace Models;
use Src\DB\Elements\Model;

class Theme extends Model
{
    protected static string $table = 'themes';
    protected static array $fields = [
        'id',
        'name',
    ];

}