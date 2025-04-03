<?php

namespace App\Exceptions;

use Exception;

class AccountNumberExists extends Exception
{
    public function __construct()
    {
        parent::__construct('account number has already generated');
    }
}
