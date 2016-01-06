<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 06.01.2016
 * Time: 12:00
 */

namespace Simplified\Validator\Contracts;

use Simplified\Validator\Contract;

class ActiveUrlContract extends Contract {
    public function isValid() {
        // TODO check our params if we have a requested record type
        $types = array('A', 'MX', 'NS', 'SOA', 'PTR', 'CNAME', 'AAAA', 'A6', 'SRV', 'NAPTR', 'TXT', 'ANY');
        foreach ($types as $type) {
            $ret = checkdnsrr($this->value(), $type);
            if (!$ret)
                return false;
        }

        return true;
    }
}