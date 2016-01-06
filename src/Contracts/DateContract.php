<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 06.01.2016
 * Time: 16:19
 */

namespace Simplified\Validator\Contracts;

use Simplified\Validator\Contract;

class DateContract extends Contract{
    public function isValid() {
        $datevalue = strtotime($this->value());
        if ($datevalue === false)
            return false;

        $params = $this->parameters();
        //if (is_null($params) || strlen($params) == 0)
        //    throw new DateContractException("Invalid rule parameter: params must not be null or empty");

        if (strpos($params, "before") === 0) {
            $colon_pos = strpos($params, ":");
            if ($colon_pos != 6)
                return false;

            $datestr = substr($params, $colon_pos+1);
            $datetime = strtotime($datestr);
            if ($datetime === false) {
                $this->error = "Invalid date parameter";
                // throw new DateContractException("Invalid rule parameter: invalid date");
            }
            $rightdate = date("Y-m-d 00:00:00", $datetime);
            $leftdate  = date("Y-m-d 00:00:00", $datevalue);
            $before = strtotime($leftdate) < strtotime($rightdate);
            return $before;
        }
        else
            if (strpos($params, "after") === 0) {
                $colon_pos = strpos($params, ":");
                if ($colon_pos != 5)
                    return false;

                $datestr = substr($params, $colon_pos+1);
                $datetime = strtotime($datestr);
                if ($datetime === false) {
                    $this->error = "Invalid date parameter";
                    // throw new DateContractException("Invalid rule parameter: invalid date");
                }
                $rightdate = date("Y-m-d 00:00:00", $datetime);
                $leftdate  = date("Y-m-d 00:00:00", $datevalue);
                $after = strtotime($leftdate) > strtotime($rightdate);
                return $after;
            }
            else
                if (strpos($params, "equal") === 0) {
                    $colon_pos = strpos($params, ":");
                    if ($colon_pos != 5)
                        return false;

                    $datestr = substr($params, $colon_pos+1);
                    $datetime = strtotime($datestr);
                    if ($datetime === false) {
                        $this->error = "Invalid date parameter";
                        // throw new DateContractException("Invalid rule parameter: invalid date");
                    }
                    $rightdate = date("Y-m-d 00:00:00", $datetime);
                    $leftdate  = date("Y-m-d 00:00:00", $datevalue);
                    $equal = strtotime($leftdate) == strtotime($rightdate);
                    return $equal;
                }
                else {
                    $colon_pos = strpos($params, ":");
                    if ($colon_pos !== false) {
                        $this->error = "Invalid date parameter";
                        // throw new DateContractException("Invalid rule parameter: date dont have a colon");
                    }

                    $datestr = $params;
                    $datetime = strtotime($datestr);
                    if ($datetime === false) {
                        $this->error = "Invalid date parameter";
                        // throw new DateContractException("Invalid rule parameter: invalid date");
                    }
                    $rightdate = date("Y-m-d 00:00:00", $datetime);
                    $leftdate  = date("Y-m-d 00:00:00", $datevalue);
                    $equal = strtotime($leftdate) == strtotime($rightdate);
                    return $equal;
                }
    }
}