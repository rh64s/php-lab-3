<?php

namespace Src\Auth;

interface IdentityInterface
{
    public function findIdentity(int $id);

    public function getId(): int; // мне лень перерабатывать магию получения атрибутов и переопределять магические методы :(

    public function attemptIdentity(array $credentials);
}