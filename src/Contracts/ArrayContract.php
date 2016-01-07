<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 07.01.2016
 * Time: 06:53
 */

namespace Simplified\Validator\Contracts;

use Simplified\Validator\Contract;

class ArrayContract extends Contract{
    public function isValid() {
        if ( is_array($this->value()) )
            return true;

        $this->error = "Value is not a array";
        return false;
    }
}