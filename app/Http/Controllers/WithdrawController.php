<?php

namespace App\Http\Controllers;



use App\Data\withdrawData;
use App\Http\Requests\withdrawRequest;
use App\Models\User;
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
     * @throws \Exception
     */
    public function store(withdrawRequest $request){
        $user=User::find(1);
        $account=$this->accountService->getAccountNumberByUserId($user->id);
        $data=new withdrawData();
        $data->setAccountNumber($account->id);
        $data->setAmount($request->amount);
        $data->setDescription($request->description);
        $data->setPin($request->pin);

        $this->accountService->withdraw($data);

        return $this->sendResponse([],'withdraw successfully');


    }
}
