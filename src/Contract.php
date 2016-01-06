<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 06.01.2016
 * Time: 10:53
 */

namespace Simplified\Validator;


abstract class Contract {
    protected $value;
    protected $params;

    public function __construct($value, $params = array()) {
        $this->value  = $value;
        $this->params = $params;
    }

    public function value() {
        return $this->value;
    }

    public function parameters() {
        return $this->params;
    }

    public function isValid() {
        return true;
    }
}