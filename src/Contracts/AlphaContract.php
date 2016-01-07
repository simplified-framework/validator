<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 06.01.2016
 * Time: 18:32
 */

namespace Simplified\Validator\Contracts;

use Simplified\Validator\Contract;

class AlphaContract extends Contract{
    public function isValid() {
        if ( preg_match('/^[\pL]+$/u', $this->value()) )
            return true;

        $this->error = "Not valid alphabetical characters";
        return false;
    }
}