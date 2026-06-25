<?php

namespace Models;

use Src\DB\Elements\Model;

class Roles extends Model
{
    protected static string $table = 'roles';
    protected static array $fields = [
        'id',
        'name'
    ];
}