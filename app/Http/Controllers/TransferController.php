<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\services\AccountService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    use ApiResponseTrait;
    public function __construct(public  AccountService $accountService)
    {
    }

    public function store(TransferRequest $request){
$user=$request->user();

$senderAccount=$this->accountService->getAccountNumberByUserId($user->id);
$this->accountService->transfer($senderAccount->account_number,$request->receiver_account_number,$request->pin,$request->amount,$request->description);
        return $this->sendResponse([], 'transfer successfully.');

    }
}
