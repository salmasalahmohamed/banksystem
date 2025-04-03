<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\QueryException;
use Throwable;

class Handler implements ExceptionHandler
{
 use ApiResponseTrait;
    public function report(Throwable $e)
    {
        // TODO: Implement report() method.
    }

    public function shouldReport(Throwable $e)
    {
        // TODO: Implement shouldReport() method.
    }

    public function render($request, Throwable $e)
    {
if ($request->expectsJson()){
    if ($e instanceof  QueryException){
        return $this->sendError([
            'message'=>$e->getMessage()
        ]);
    }
    if ($e instanceof  AccountNumberExists){
        return $this->sendError([
            'message'=>$e->getMessage()
        ]);
    }
    if ($e instanceof  Amounttolow){
        return $this->sendError([
            'message'=>$e->getMessage()
        ]);
    }
}

    }

    public function renderForConsole($output, Throwable $e)
    {
        // TODO: Implement renderForConsole() method.
    }
}
