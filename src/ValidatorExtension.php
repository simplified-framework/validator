<?php

use Simplified\Validator\Validator;
use Simplified\Validator\ValidationException;
use Simplified\Core\Lang;

Validator::extend('accepted', function($attributes, $value){
    if ($value === true || $value === 1)
        return ['valid' => true];

    if (is_string($value)) {
        $lower = strtolower($value);
        if ($lower == "on" || $lower == "yes")
            return ['valid' => true];

        $yes = Lang::get('validator.yes');
        $no  = Lang::get('validator.no');
        if ($yes && $no) {
            if ($lower == $yes || $lower == $no) {
                return ['valid' => true];
            }
        }
    }

    $message = Lang::get('validator.not_accepted');
    if (!$message)
        $message = 'Not accepted';
    return ['valid' => false, 'error' => $message];
});

Validator::extend('after', function($attribute, $value) {
    if (count($attribute) != 1)
        throw new ValidationException('Attribute must have a date value (after:date_value)');

    $datetime_right = strtotime($attribute[0]);
    if (!$datetime_right)
        throw new ValidationException('Attribute must have a date value (after:date_value)');

    $datetime_left = strtotime($value);
    if (!$datetime_left) {
        $message = Lang::get('validator.invalid_date');
        if (!$message)
            $message = 'Invalid date';
        return ['valid' => false, 'error' => $message];
    }

    $date_left = strtotime(date("Y-m-d 00:00:00", $datetime_left));
    $date_right = strtotime(date("Y-m-d 00:00:00", $datetime_right));
    $valid = $date_left > $date_right;

    $message = Lang::get('validator.date_not_after', array('after' => $attribute[0]));
    if (!$message)
        $message = "Date is not after {$attribute[0]}";
    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];
});

Validator::extend('alpha', function($attribute, $value) {
    $valid = preg_match('/^[\pL]+$/u', $value);

    $message = Lang::get('validator.value_not_alphabetic');
    if (!$message)
        $message = "Value is not alphabetic";

    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];
});

Validator::extend('alpha_dash', function($attribute, $value) {
    $valid = preg_match('/^[\pL\-]+$/u', $value);

    $message = Lang::get('validator.value_not_alphabetic_with_dash');
    if (!$message)
        $message = "Value is not alphabetic with dash(es)";

    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];
});

Validator::extend('alpha_num', function($attribute, $value) {
    $valid = preg_match('/^[\pL0-9]+$/', $value);

    $message = Lang::get('validator.value_not_alphanumeric');
    if (!$message)
        $message = "Value is not alph anumeric";

    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];
});

Validator::extend('alpha_spaces', function($attribute, $value) {
    $valid = preg_match('/^[\pL\s]+$/u', $value);

    $message = Lang::get('validator.value_not_alpha_with_spaces');
    if (!$message)
        $message = "Value is not alpha with space(s)";

    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];
});

Validator::extend('array', function($attribute, $value) {
    $valid = is_array($value);

    $message = Lang::get('validator.value_not_array');
    if (!$message)
        $message = "Value is not a array";

    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];
});

Validator::extend('before', function($attribute, $value) {
    if (count($attribute) != 1)
        throw new ValidationException('Attribute must have a date value (before:date_value)');

    $datetime_right = strtotime($attribute[0]);
    if (!$datetime_right)
        throw new ValidationException('Attribute must have a date value (before:date_value)');

    $datetime_left = strtotime($value);
    if (!$datetime_left) {
        $message = Lang::get('validator.invalid_date');
        if (!$message)
            $message = "Invalid date";
        return ['valid' => false, 'error' => $message];
    }

    $date_left = strtotime(date("Y-m-d 00:00:00", $datetime_left));
    $date_right = strtotime(date("Y-m-d 00:00:00", $datetime_right));
    $valid = $date_left < $date_right;

    $message = Lang::get('validator.date_not_before', array('before' => $attribute[0]));
    if (!$message)
        $message = "Date is not before {$attribute[0]}";

    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];
});

Validator::extend('between', function($attribute, $value){
    if (count($attribute) != 2)
        throw new ValidationException('Attribute must have min and max value (between:min_value,max_value)');

    $min = $attribute[0];
    $max = $attribute[1];
    $valid = is_numeric($value) && $value > $min && $value < $max;

    $message = Lang::get('validator.value_not_between', array('min' => $min, 'max' => $max));
    if (!$message)
        $message = "Value is not between [$min} and {$max}";

    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];
});

Validator::extend('boolean', function($attribute, $value) {
    if ($value === true || $value === false)
        return ['valid' => true];

    if ($value == '1' || $value == '0')
        return ['valid' => true];

    if ($value == 'yes' || $value == 'no')
        return ['valid' => true];

    $message = Lang::get('validator.value_not_boolean');
    if (!$message)
        $message = "Value is not a boolean";

    return ['valid' => false, 'error' => $message];
});

Validator::extend('date', function($attribute, $value) {
    $valid = strtotime($value);

    $message = Lang::get('validator.value_not_date');
    if (!$message)
        $message = "Value is not a date";

    $error = $valid ? null : $message;
    return ['valid' => ($valid !== false), 'error' => $error];

});

Validator::extend('different', function($attribute, $value) {
    if (!isset($attribute[0]))
        throw new ValidationException('Attribute must have field name (different:field_name)');

    $other = self::$request->input($attribute[0]);
    $valid = $other != $value;

    $message = Lang::get('validator.value_not_different');
    if (!$message)
        $message = "Value is not different";

    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];
});

Validator::extend('integer', function($attribute, $value) {
    $valid = is_numeric($value);

    $message = Lang::get('validator.value_not_integer');
    if (!$message)
        $message = "Value is not a integer";

    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];
});

Validator::extend('max', function($attribute, $value) {
    if (!isset($attribute[0]))
        throw new ValidationException('Attribute must have max value (max:value)');

    $valid = is_numeric($value);
    if (!$valid) {
        $message = Lang::get('validator.value_not_numeric');
        if (!$message)
            $message = "Value is not numeric";
        return ['valid' => false, 'error' => $message];
    }

    $max = $attribute[0];
    $valid = $value <= $max;

    $message = Lang::get('validator.value_greater_than', array('value' => $max));
    if (!$message)
        $message = "Value is greater than {$max}";

    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];
});

Validator::extend('min', function($attribute, $value) {
    if (!isset($attribute[0]))
        throw new ValidationException('Attribute must have min value (min:value)');

    $valid = is_numeric($value);
    if (!$valid) {
        $message = Lang::get('validator.value_not_numeric');
        if (!$message)
            $message = "Value is not numeric";
        return ['valid' => false, 'error' => $message];
    }

    $min = $attribute[0];
    $valid = $value >= $min;

    $message = Lang::get('validator.value_less_than', array('value' => $min));
    if (!$message)
        $message = "Value is less than {$min}";

    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];
});

Validator::extend('numeric', function($attribute, $value) {
    $valid = is_numeric($value);

    $message = Lang::get('validator.value_not_numeric');
    if (!$message)
        $message = "Value is not numeric";

    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];

});

Validator::extend('same', function($attribute, $value) {
    if (!isset($attribute[0]))
        throw new ValidationException('Attribute must have field name (different:field_name)');

    $other = self::$request->input($attribute[0]);
    $valid = $other == $value;

    $message = Lang::get('validator.value_not_equal', array('other' => $other));
    if (!$message)
        $message = "Value is not equal to {$other}";

    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];
});

Validator::extend('size', function($attribute, $value) {
    if (!isset($attribute[0]) || !is_numeric($attribute[0]))
        throw new ValidationException('Attribute must have a numeric size value (size:value)');

    $size = $attribute[0];
    if (is_string($value)) {
        $valid = strlen($value) == $size;

        $message = Lang::get('validator.value_not', array('size' => $size));
        if (!$message)
            $message = "Value is not {$size}";

        $error = $valid ? null : $message;
        return ['valid' => $valid, 'error' => $error];
    }

    if (is_numeric($value)) {
        $valid = $value == $size;
        $message = Lang::get('validator.value_not', array('size' => $size));
        if (!$message)
            $message = "Value is not {$size}";

        $error = $valid ? null : $message;
        return ['valid' => $valid, 'error' => $error];
    }

    $message = Lang::get('validator.value_not_size');
    if (!$message)
        $message = "Value is not a size";

    return ['valid' => false, 'error' => $message];
});

Validator::extend('string', function($attribute, $value) {
    $valid = is_string($value);

    $message = Lang::get('validator.value_not_string');
    if (!$message)
        $message = "Value is not a string";

    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];
});

Validator::extend('token', function($attribute, $value) {
    $valid = isset($_SESSION['_token']) && $_SESSION['_token'] == $value;

    $message = Lang::get('validator.invalid_token');
    if (!$message)
        $message = "Token is invalid";

    $error = $valid ? null : $message;
    return ['valid' => $valid, 'error' => $error];
});
