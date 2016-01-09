<?php

use Simplified\Validator\Validator;
use Simplified\Validator\ValidationException;

Validator::extend('accepted', function($attributes, $value){
    if ($value === true || $value === 1)
        return ['valid' => true];

    if (is_string($value)) {
        $lower = strtolower($value);
        if ($lower == "on" || $lower == "yes")
            return ['valid' => true];
    }

    return ['valid' => false, 'error' => 'Not accepted'];
});

Validator::extend('after', function($attribute, $value) {
    if (count($attribute) != 1)
        throw new ValidationException('Attribute must have a date value (after:date_value)');

    $datetime_right = strtotime($attribute[0]);
    if (!$datetime_right)
        throw new ValidationException('Attribute must have a date value (after:date_value)');

    $datetime_left = strtotime($value);
    if (!$datetime_left)
        return ['valid' => false, 'error' => 'Invalid date'];

    $date_left = strtotime(date("Y-m-d 00:00:00", $datetime_left));
    $date_right = strtotime(date("Y-m-d 00:00:00", $datetime_right));
    $valid = $date_left > $date_right;
    $error = $valid ? null : array('error' => "Date is not after {$attribute[0]}");
    return ['valid' => $valid, $error];
});

Validator::extend('alpha', function($attribute, $value) {
    $valid = preg_match('/^[\pL]+$/u', $value);
    $error = $valid ? null : array('error' => "Value is not alphabetic");
    return ['valid' => $valid, $error];
});

Validator::extend('alpha_dash', function($attribute, $value) {
    $valid = preg_match('/^[\pL\-]+$/u', $value);
    $error = $valid ? null : array('error' => "Value is not alphabetic with dash");
    return ['valid' => $valid,$error];
});

Validator::extend('alpha_num', function($attribute, $value) {
    $valid = preg_match('/^[\pL0-9]+$/', $value);
    $error = $valid ? null : array('error' => "Value is not alpha-numeric");
    return ['valid' => $valid, $error];
});

Validator::extend('alpha_spaces', function($attribute, $value) {
    $valid = preg_match('/^[\pL\s]+$/u', $value);
    $error = $valid ? null : array('error' => "Value is not alpha with spaces");
    return ['valid' => $valid, $error];
});

Validator::extend('array', function($attribute, $value) {
    $valid = is_array($value);
    $error = $valid ? null : array('error' => "Value is not a array");
    return ['valid' => $valid, $error];
});

Validator::extend('before', function($attribute, $value) {
    if (count($attribute) != 1)
        throw new ValidationException('Attribute must have a date value (before:date_value)');

    $datetime_right = strtotime($attribute[0]);
    if (!$datetime_right)
        throw new ValidationException('Attribute must have a date value (before:date_value)');

    $datetime_left = strtotime($value);
    if (!$datetime_left)
        return ['valid' => false];

    $date_left = strtotime(date("Y-m-d 00:00:00", $datetime_left));
    $date_right = strtotime(date("Y-m-d 00:00:00", $datetime_right));
    $valid = $date_left < $date_right;
    $error = $valid ? null : array('error' => "Date is not before {$attribute[0]}");
    return ['valid' => $valid, $error];
});

Validator::extend('between', function($attribute, $value){
    if (count($attribute) != 2)
        throw new ValidationException('Attribute must have min and max value (between:min_value,max_value)');

    $min = $attribute[0];
    $max = $attribute[1];
    $valid = is_numeric($value) && $value > $min && $value < $max;
    $error = $valid ? null : array('error' => "Value is not between [$min} and {$max}");
    return ['valid' => $valid, $error];
});

Validator::extend('boolean', function($attribute, $value) {
    if ($value === true || $value === false)
        return ['valid' => true];

    if ($value == '1' || $value == '0')
        return ['valid' => true];

    if ($value == 'yes' || $value == 'no')
        return ['valid' => true];

    $error = array('error' => "Value is not boolean");
    return ['valid' => false, $error];
});

Validator::extend('date', function($attribute, $value) {
    $valid = strtotime($value);
    $error = $valid ? null : array('error' => "Value is not a date");
    return ['valid' => ($valid !== false), $error];

});

Validator::extend('different', function($attribute, $value) {
    if (!isset($attribute[0]))
        throw new ValidationException('Attribute must have field name (different:field_name)');

    $other = self::$request->input($attribute[0]);
    $valid = $other != $value;
    $error = $valid ? null : array('error' => "Value is not different");
    return ['valid' => $valid, $error];
});

Validator::extend('integer', function($attribute, $value) {
    $valid = is_numeric($value);
    $error = $valid ? null : array('error' => "Value is not a integer");
    return ['valid' => $valid, $error];

});

Validator::extend('max', function($attribute, $value) {
    if (!isset($attribute[0]))
        throw new ValidationException('Attribute must have max value (max:value)');

    $valid = is_numeric($value);
    if (!$valid)
        return ['valid' => $valid];

    $max = $attribute[0];
    $valid = $value <= $max;
    $error = $valid ? null : array('error' => "Value is greater than {$max}");
    return ['valid' => $valid, $error];
});

Validator::extend('min', function($attribute, $value) {
    if (!isset($attribute[0]))
        throw new ValidationException('Attribute must have min value (min:value)');

    $valid = is_numeric($value);
    if (!$valid)
        return ['valid' => $valid];

    $min = $attribute[0];
    $valid = $value >= $min;
    $error = $valid ? null : array('error' => "Value is less than {$min}");
    return ['valid' => $valid];
});

Validator::extend('numeric', function($attribute, $value) {
    $valid = is_numeric($value);
    $error = $valid ? null : array('error' => "Value is not numeric");
    return ['valid' => $valid,$error];

});

Validator::extend('same', function($attribute, $value) {
    if (!isset($attribute[0]))
        throw new ValidationException('Attribute must have field name (different:field_name)');

    $other = self::$request->input($attribute[0]);
    $valid = $other == $value;
    $error = $valid ? null : array('error' => "Value is not equal to {$other}");
    return ['valid' => $valid, $error];
});

Validator::extend('size', function($attribute, $value) {
    if (!isset($attribute[0]) || !is_numeric($attribute[0]))
        throw new ValidationException('Attribute must have a numeric size value (size:value)');

    $size = $attribute[0];
    if (is_string($value)) {
        $valid = strlen($value) == $size;
        $error = $valid ? null : array('error' => "Value is not {$size}");
        return ['valid' => $valid,$error];
    }

    if (is_numeric($value)) {
        $valid = $value == $size;
        $error = $valid ? null : array('error' => "Value is not {$size}");
        return ['valid' => $valid,$error];
    }

    $error = array('error' => "Value is not a size");
    return ['valid' => false, $error];

});

Validator::extend('string', function($attribute, $value) {
    $valid = is_string($value);
    $error = $valid ? null : array('error' => "Value is not a string");
    return ['valid' => $valid, $error];

});

Validator::extend('token', function($attribute, $value) {
    $valid = isset($_SESSION['_token']) && $_SESSION['_token'] == $value;
    $error = $valid ? null : array('error' => "Value is invalid");
    return ['valid' => $valid, $error];
});
