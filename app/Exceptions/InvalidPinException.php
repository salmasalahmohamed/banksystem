<?php

namespace App\Exceptions;
use Exception;
class InvalidPinException extends Exception
{
    public function __construct()
    {
        parent::__construct('invalid exception');
    }
}
