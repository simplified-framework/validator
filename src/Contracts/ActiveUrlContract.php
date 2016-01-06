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
        $services = array('A', 'MX', 'NS', 'SOA', 'PTR', 'CNAME', 'AAAA', 'A6', 'SRV', 'NAPTR', 'TXT', 'ANY');
    }
}