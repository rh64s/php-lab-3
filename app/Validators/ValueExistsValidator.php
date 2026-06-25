<?php

namespace Validators;

use Src\DB\QueryBuilder;
use Src\Validator\ValidatorAbstract;

class ValueExistsValidator extends ValidatorAbstract
{
    public function rule(): bool
    {
        return (bool) (new QueryBuilder($this->args[0]))->where($this->args[1], $this->value)->first();
    }
}
