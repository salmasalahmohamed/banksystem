<?php

namespace App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use Throwable;

class InvalidPinLength extends Exception
{
    public function __construct()
    {
        parent::__construct('length of pin must equal 4');
    }
}
