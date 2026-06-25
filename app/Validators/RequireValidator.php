<?php

namespace Validators;

use Src\Validator\ValidatorAbstract;

class RequireValidator extends ValidatorAbstract
{
    public function rule(): bool
    {
        return !empty($this->value);
    }
}
