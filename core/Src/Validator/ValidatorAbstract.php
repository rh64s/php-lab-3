<?php

namespace Src\Validator;

abstract class ValidatorAbstract
{
    protected mixed $value;
    protected array $args;

    public function __construct(mixed $value, array $args = [])
    {
        $this->value = $value;
        $this->args = $args;
    }
    public function validate(): bool
    {
        return $this->rule();
    }

    abstract public function rule(): bool;
}