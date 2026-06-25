<?php

namespace Models;

use Src\DB\Elements\Model;

class Answers extends Model
{
    protected static string $table = 'answers';
    protected static array $fields = [
        'id',
        'user_id',
        'question_id',
        'text'
    ];
}