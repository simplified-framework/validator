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

    public function __construct() {
        $ns = strtolower(__NAMESPACE__);
        $ns = str_replace("\\", DIRECTORY_SEPARATOR, $ns);
        $target = I18N_PATH . "en" . DIRECTORY_SEPARATOR . "validator.php";
        $source = VENDOR_PATH . $ns . DIRECTORY_SEPARATOR . "dist" . DIRECTORY_SEPARATOR . "validator.php";

        if (!file_exists($target)) {
            copy($source, $target);
            return;
        }

        $t = file_get_contents($target);
        $s = file_get_contents($source);
        if (md5($t) != md5($s)) {
            copy($source, $target);
            return;
        }
    }

    public function validate(Request $request, array $rules) {
        self::$request = $request;

        $valid = true;
        foreach ($rules as $field => $rule) {
            if (is_null($rule) || !is_string($rule) || strlen($rule) == 0)
                throw new ValidationException("Rule must be a string");

            if (isset($this->validationErrors[$field]))
                break;

            // get field value
            $value = $request->input($field);

            // split validation rule into required / sometimes (no validation if not set or empty)
            // contract and contract parameters
            $parts = explode("|", $rule);
            if (is_null($parts) || !is_array($parts))
                throw new ValidationException("Invalid rule");

            $required = strtolower(array_shift($parts)) == "required" ? true : false;
            if (($required && is_null($value)) || ($required && empty($value))) {
                $valid = false;
                $this->validationErrors[$field] = "Field {$field} has no value";
                continue;
            }

            // rule has no attributes
            if (count($parts) == 0)
                break;

            foreach ($parts as $part) {
                if (!$valid)
                    continue;

                $ext_parts = explode(":", $part);
                $ext_orig = trim($ext_parts[0]);
                $ext = strtolower($ext_orig);

                if (!isset(self::$extensions[$ext])) {
                    throw new ValidationException("Unknown extension '{$ext_orig}''");
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
                $returnValue = call_user_func_array($extension, array($attrs, $value));
                if (!isset($returnValue['valid']))
                    throw new \InvalidArgumentException("Missing return value from extension {$ext_orig}");

                if (!$returnValue['valid']) {
                    $valid = false;
                    $this->validationErrors[$field] = (isset($returnValue['error']) ? $returnValue['error'] : 'Unknown validation error');
                    continue;
                }
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
