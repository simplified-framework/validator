<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 06.01.2016
 * Time: 10:53
 */

namespace Simplified\Validator;


abstract class Contract {
    protected $field;
    protected $value;
    protected $params;
    protected $error;

    public function __construct($field, $value, $params = array()) {
        $this->field  = $field;
        $this->value  = $value;
        $this->params = $params;
    }

    public function field() {
        return $this->field;
    }

    public function value() {
        return $this->value;
    }

    public function parameters() {
        return $this->params;
    }

    public function error() {
        return $this->error;
    }

    abstract public function isValid();
}