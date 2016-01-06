<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 06.01.2016
 * Time: 11:53
 */

namespace Simplified\Validator\Contracts;

use Simplified\Validator\Contract;

class AcceptedContract extends Contract {
    public function isValid() {
        if ($this->value() === true || $this->value() === 1)
            return true;

        if (is_string($this->value())) {
            $lower = strtolower($this->value());
            if ($lower == "on" || $lower == "yes")
                return true;
        }

        return false;
    }
}