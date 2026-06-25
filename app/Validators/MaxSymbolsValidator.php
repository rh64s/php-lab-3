<?php

namespace Validators;

use DebugTools\Debug;
use Src\Validator\ValidatorAbstract;

class MaxSymbolsValidator extends ValidatorAbstract
{
    public function rule(): bool
    {
        return (strlen($this->value) <= (int)$this->args[0]);
    }
}
