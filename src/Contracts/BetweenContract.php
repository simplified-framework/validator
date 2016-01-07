<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 07.01.2016
 * Time: 06:55
 */

namespace Simplified\Validator\Contracts;

use Simplified\Validator\Contract;
use Simplified\Core\IllegalArgumentException;

class BetweenContract extends Contract{
    public function isValid() {
        if (!is_numeric($this->value()))
            return false;

        $params = $this->parameters();
        $parts = explode(",", $params);
        if (count($parts) != 2)
            throw new IllegalArgumentException("Between supports only comma separated min and max value");

        $a = trim($parts[0]);
        $b = trim($parts[1]);
        if (!is_numeric($a) || !is_numeric($b))
            throw new IllegalArgumentException("Min and max values must be numeric");

        if ($this->value() >= $a && $this->value() <= $b )
            return true;

        $this->error = "Value is not between {$a} and {$b}";
        return false;
    }
}