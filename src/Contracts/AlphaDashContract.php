<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 07.01.2016
 * Time: 06:49
 */

namespace Simplified\Validator\Contracts;


use Simplified\Validator\Contract;

class AlphaDash extends Contract{
    public function isValid() {
        if ( preg_match('/^[\pL\_\-]+$/u', $this->value()) )
            return true;

        $this->error = "Not valid alphabetical characters";
        return false;
    }
}