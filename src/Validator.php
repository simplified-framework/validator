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

            foreach ($parts as $part) {
                $ext_parts = explode(":", $part);
                $ext = trim($ext_parts[0]);

                if (!isset(self::$extensions[$ext])) {
                    throw new ValidationException("Unknown extension '{$ext}''");
                }

                $attrs = array();
                if (isset($ext_parts[1])) {
                    $ext_attr_parts = trim($ext_parts[1]);
                    $attrs_tmp = explode(",", $ext_attr_parts);
                    foreach ($attrs_tmp as $attr) {
                        $attrs[] = trim($attr);
                    }
                }

                $extension = self::$extensions[$ext];
                $valid = call_user_func_array($extension, array($attrs, $value));
                var_dump($valid);
            }
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
