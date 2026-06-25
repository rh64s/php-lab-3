<?php

namespace Validators;

use Src\Validator\ValidatorAbstract;

class MinSymbolsValidator extends ValidatorAbstract
{
    public function rule(): bool
    {
        return (strlen($this->value) >= (int) $this->args[0]);
    }
}