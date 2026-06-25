<?php

namespace Validators;

use DebugTools\Debug;
use Src\Validator\ValidatorAbstract;

class MinSymbolsValidator extends ValidatorAbstract
{
    public function rule(): bool
    {
        Debug::info((bool)strlen($this->value) >= (int) $this->args[0]);
        return (strlen($this->value) >= (int) $this->args[0]);
    }
}