<?php

namespace Simplified\Validator\Contracts;

use Simplified\Validator\Contract;

class NullContract extends Contract {
    public function isValid() {
        if (is_null($this->value())) {
            return true;
        }

        return false;
    }
}