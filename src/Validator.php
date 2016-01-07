<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 06.01.2016
 * Time: 10:44
 */

namespace Simplified\Validator;

use Simplified\Http\Request;
use Doctrine\Common\Inflector\Inflector;
use Simplified\Validator\Contracts\TokenContract;

/*
 * Validator::extend('alpha_spaces', function($attribute, $value)
{
    return preg_match('/^[\pL\s]+$/u', $value);
});
 */

trait Validator {
    private $validationErrors = array();
    public function validate(Request $request, array $rules) {
        if ($request->getMethod() == "POST") {
            if ($request->input('_token')) {
                $tc = new TokenContract('_token',$request->input('_token'));
                $tc->isValid();
            }
        }

        $valid = true;
        foreach ($rules as $field => $rule) {
            if (is_null($rule) || !is_string($rule) || strlen($rule) == 0)
                throw new ValidationException("Rule must be a string");

            // get field value
            $value = $request->input($field);

            // split validation rule into required / sometimes (no validation if not set or empty)
            // contract and contract parameters
            $parts = explode("|", $rule);
            if (is_null($parts) || !is_array($parts) || count($parts) <2)
                throw new ValidationException("Invalid rule");

            $required = strtolower($parts[0]) == "required" ? true : false;
            if ($required && is_null($value)) {
                $valid = false;
                $this->validationErrors[] = "Field has no value";
                continue;
            }

            $clazz = __NAMESPACE__. "\\Contracts\\" . Inflector::classify($parts[1])."Contract";
            if (!class_exists($clazz))
                throw new ValidationException("Invalid validation class '$clazz' in rule.");

            $instance = new $clazz($field, $value, isset($parts[2]) ? $parts[2]:null);
            if (!$instance instanceof Contract)
                throw new ValidationException("'$clazz' must extend Simplified\\Validator\\Contract");

            if (!$instance->isValid()) {
                $this->validationErrors[] = $instance->error();
                $valid = false;
            }
        }

        return $valid;
    }

    public function validationErrors() {
        return $this->validationErrors;
    }
}