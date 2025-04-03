<?php

namespace App\Exceptions;

use Exception;

class Amounttolow extends Exception
{
    public function __construct()
    {
        parent::__construct(' deposit amount too low');
    }
}
