<?php

namespace App\Http\Controllers;

use App\Data\DepositData;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\withdrawRequest;
use App\services\AccountService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use ApiResponseTrait;
    public AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }
    public function store(Request $request){
$this->accountService->createAccountNumber($request->userdata,$request->id);
        return $this->sendResponse([], 'account number generate successfully.');

    }

}
