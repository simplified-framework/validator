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
            throw new TokenNotFoundException("The CSRF token can not be found in request or session");
        }

        if ($_SESSION[$this->field()] != $this->value()) {
            throw new TokenMismatchException("Session token and form token are not equal");
        }

        return true;
    }
}