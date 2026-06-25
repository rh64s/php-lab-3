<?php

namespace Models;

use Src\DB\Elements\Model;

class AnswerNotification extends Model
{
    protected static string $table = 'answer_notifications';
    protected static array $fields = [
        'id',
        'answer_id',
        'is_read',
        'user_id'
    ];
}