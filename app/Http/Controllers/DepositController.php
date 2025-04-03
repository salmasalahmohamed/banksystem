<?php

namespace App\Http\Controllers;



use App\Data\DepositData;
use App\Http\Requests\DepositRequest;
use App\services\AccountService;
use App\Traits\ApiResponseTrait;

class DepositController extends Controller
{   use ApiResponseTrait;
    public AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }
    public function store(DepositRequest $request){
        $data=new DepositData();
        $data->setAccountNumber($request->account_number);
        $data->setAmount($request->amount);
        $data->setDescription($request->description);
        $this->accountService->deposit($data);
        return $this->sendResponse([],'deposit successfully');
    }

}
