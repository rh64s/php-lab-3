<?php

namespace Models;
use Src\DB\Elements\Model;

class Questions extends Model
{
    protected static string $table = 'questions';
    protected static array $fields = [
        'id',
        'user_id',
        'theme_id',
        'text',
        'completed',
    ];
}