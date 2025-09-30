<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Models\User;
use App\services\AccountService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    use ApiResponseTrait;
    public AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function store(TransferRequest $request){
$user=$request->user();
$senderAccount=$this->accountService->getAccountNumberByUserId($user->id);
$this->accountService->transfer($senderAccount->id,$request->receiver_account_number,$request->pin,$request->amount,$request->description);
        return $this->sendResponse([], 'transfer successfully.');

    }
}
