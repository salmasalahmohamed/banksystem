<?php

namespace App\Exceptions;
use Exception;

class InvalidAccountNumberException extends Exception
{

    public function __construct()
    {
        parent::__construct(' invalid account number');
    }
}
