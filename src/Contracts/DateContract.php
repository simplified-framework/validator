<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 06.01.2016
 * Time: 16:19
 */

namespace Simplified\Validator\Contracts;

use Simplified\Validator\Contract;

class DateContract extends Contract{
    public function isValid() {
        $timestamp = strtotime($this->value());
        if ($timestamp === false)
            return false;

        var_dump($this->parameters());
    }
}