<?php

namespace Models;

use DebugTools\Debug;
use Src\Auth\IdentityInterface;
use Src\DB\Elements\Model;

class User extends Model implements IdentityInterface
{
    protected static string $table = 'users';

    protected static array $fields = [
        'id',
        'login',
        'role_id',
        'password',
    ];

    //Выборка пользователя по первичному ключу
    public function findIdentity(int $id): ?static
    {
        return self::where('id', $id)->first();
    }

    //Возврат аутентифицированного пользователя
    public function attemptIdentity(array $credentials): ?static
    {
        Debug::log($credentials);
        return self::where('login', $credentials['login'])->where('password', md5($credentials['password']))->first();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function __construct(array $data = [])
    {
        $data['password'] = md5($data['password']);
        parent::__construct($data);
    }
}