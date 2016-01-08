<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 06.01.2016
 * Time: 10:44
 */

namespace Simplified\Validator;

use Simplified\Core\IllegalArgumentException;
use Simplified\Http\Request;
use Simplified\Validator\Contracts\TokenContract;

trait Validator {
    private static $extensions = array();
    private $validationErrors = array();
    private static $request;

    public function validate(Request $request, array $rules) {
        self::$request = $request;

        $valid = true;
        foreach ($rules as $field => $rule) {
            if (is_null($rule) || !is_string($rule) || strlen($rule) == 0)
                throw new ValidationException("Rule must be a string");

            // get field value
            $value = $request->input($field);

            // split validation rule into required / sometimes (no validation if not set or empty)
            // contract and contract parameters
            $parts = explode("|", $rule);
            if (is_null($parts) || !is_array($parts))
                throw new ValidationException("Invalid rule");

            $required = strtolower(array_shift($parts)) == "required" ? true : false;
            if ($required && is_null($value)) {
                $valid = false;
                $this->validationErrors[] = "Field {$field} has no value";
                continue;
            }

            // rule has no attributes
            if (count($parts) == 0)
                break;

            /*
            $methodName = strtolower(array_shift($parts));
            if (!isset(self::$extensions[$methodName])) {
                throw new IllegalArgumentException("Validator extension '{$methodName}' isn't registered");
            }

            $extension = self::$extensions[$methodName];
            $returnValue = call_user_func_array($extension, array($parts, $value));

            if ($returnValue['valid'] === false) {
                $this->validationErrors[$field] = $returnValue;
                $valid = false;
            }
            */
        }

        return $valid;
    }

    public function validationErrors() {
        return $this->validationErrors;
    }

    public static function extend($name, \Closure $impl) {
        self::$extensions[$name] = $impl;
    }
}
