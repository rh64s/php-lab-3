<?php

namespace Validators;

use Src\Validator\ValidatorAbstract;

class RawRegexValidator extends ValidatorAbstract
{
    public function rule(): bool
    {
        return (bool)preg_match($this->args[0], $this->value);
    }
}