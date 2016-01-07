<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 07.01.2016
 * Time: 07:14
 */

namespace Simplified\Validator\Contracts;

use Simplified\Validator\Contract;

class BooleanContract extends Contract{
    public function isValid() {
        if (is_numeric($this->value())) {
            if ($this->value() == 0 || $this->value() == 1)
                return true;
        }

        if (is_bool($this->value()))
            return true;

        $this->error = "Value is not a boolean value";
        return false;
    }
}