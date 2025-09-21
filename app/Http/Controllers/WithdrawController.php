<?php

namespace App\Http\Controllers;



use App\Data\withdrawData;
use App\Http\Requests\withdrawRequest;
use App\services\AccountService;
use App\Traits\ApiResponseTrait;

class WithdrawController extends Controller
{
    use ApiResponseTrait;
    public AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * @throws \App\Exceptions\InvalidPinException
     */
    public function store(withdrawRequest $request){
        $account=$this->accountService->getAccountNumberByUserId($request->user()->id);
        $data=new withdrawData();
        $data->setAccountNumber($account->account_number);
        $data->setAmount($request->amount);
        $data->setDescription($request->description);
        $data->setPin($request->pin);
        $this->accountService->withdraw($data);
        return $this->sendResponse([],'withdraw successfully');


    }
}
