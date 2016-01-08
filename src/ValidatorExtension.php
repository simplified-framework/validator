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

    return ['valid' => false];
});

Validator::extend('alpha', function($attribute, $value) {
    $valid = preg_match('/^[\pL]+$/u', $value);
    return ['valid' => $valid];
});

Validator::extend('alpha_spaces', function($attribute, $value) {
    $valid = preg_match('/^[\pL\s]+$/u', $value);
    return ['valid' => $valid];
});

Validator::extend('alpha_dash', function($attribute, $value) {
    $valid = preg_match('/^[\pL\-]+$/u', $value);
    return ['valid' => $valid];
});

Validator::extend('alpha_num', function($attribute, $value) {
    $valid = preg_match('/^[0-9]+$/', $value);
    return ['valid' => $valid];
});

Validator::extend('array', function($attribute, $value) {
    $valid = is_array($value);
    return ['valid' => $valid];
});

Validator::extend('between', function($attribute, $value){
    if (count($attribute) != 2)
        throw new ValidationException('Attribute must have min and max value (between:min_value,max_value)');

    $min = $attribute[0];
    $max = $attribute[1];
    $valid = is_numeric($value) && $value > $min && $value < $max;
    return ['valid' => $valid];
});

Validator::extend('boolean', function($attribute, $value) {
    if ($value === true || $value === false)
        return ['valid' => true];

    if ($value == '1' || $value == '0')
        return ['valid' => true];

    if ($value == 'yes' || $value == 'no')
        return ['valid' => true];

    return ['valid' => false];
});

Validator::extend('different', function($attribute, $value) {
    if (!isset($attribute[0]))
        throw new ValidationException('Attribute must have field name (different:field_name)');

    $other = self::$request->input($attribute[0]);
    return ['valid' => $other != $value];
});

Validator::extend('same', function($attribute, $value) {
    if (!isset($attribute[0]))
        throw new ValidationException('Attribute must have field name (different:field_name)');

    $other = self::$request->input($attribute[0]);
    return ['valid' => $other == $value];
});

Validator::extend('string', function($attribute, $value) {
    $valid = is_string($value);
    return ['valid' => $valid];

});

Validator::extend('integer', function($attribute, $value) {
    $valid = is_numeric($value);
    return ['valid' => $valid];

});

Validator::extend('min', function($attribute, $value) {
    if (!isset($attribute[0]))
        throw new ValidationException('Attribute must have min value (min:value)');

    $valid = is_numeric($value);
    if (!$valid)
        return ['valid' => $valid];

    $min = $attribute[0];
    $valid = $value >= $min;
    return ['valid' => $valid];
});

Validator::extend('max', function($attribute, $value) {
    if (!isset($attribute[0]))
        throw new ValidationException('Attribute must have max value (max:value)');

    $valid = is_numeric($value);
    if (!$valid)
        return ['valid' => $valid];

    $max = $attribute[0];
    $valid = $value <= $max;
    return ['valid' => $valid];
});

Validator::extend('numeric', function($attribute, $value) {
    $valid = is_numeric($value);
    return ['valid' => $valid];

});

Validator::extend('size', function($attribute, $value) {
    if (!isset($attribute[0]) || !is_numeric($attribute[0]))
        throw new ValidationException('Attribute must have a numeric size value (size:value)');

    $size = $attribute[0];
    if (is_string($value)) {
        $valid = strlen($value) == $size;
        return ['valid' => $valid];
    }

    if (is_numeric($value)) {
        $valid = $value == $size;
        return ['valid' => $valid];
    }

    return ['valid' => false];

});

Validator::extend('date', function($attribute, $value) {
    $valid = strotime($value);
    return ['valid' => ($valid !== false)];

});

Validator::extend('token', function($attribute, $value) {
    $valid = isset($_SESSION['_token']) && $_SESSION['_token'] == $value;
    return ['valid' => ($valid !== false)];

});
