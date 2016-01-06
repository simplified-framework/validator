<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 06.01.2016
 * Time: 13:53
 */

namespace Simplified\Validator\Contracts;

use Simplified\Validator\Contract;

class TokenContract extends Contract {
    public function isValid() {
        if (!isset($_SESSION[$this->field()]) || is_null($this->value())) {
            $this->error = "field or token is empty";
            return false;
        }

        if ($_SESSION[$this->field()] != $this->value()) {
            $this->error = "Session and token are not equal";
            return false;
        }

        return true;
    }
}