<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 07.01.2016
 * Time: 06:51
 */

namespace Simplified\Validator\Contracts;

use Simplified\Validator\Contract;

class AlphaNum extends Contract {
    public function isValid() {
        if ( preg_match('/[0-9]+/', $this->value()) )
            return true;

        $this->error = "Not valid numeric characters";
        return false;
    }
}