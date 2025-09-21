<?php

namespace App\Http\Controllers;

use App\services\TransactionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use ApiResponseTrait;
    public function __construct(public TransactionService $transactionService)
    {
    }

    public function index(Request $request){
        $transactionBuilder=$this->transactionService->modelQuery()->when($request->category,function ($q) use($request){
            $q->where('category',$request->category);
        });
        $this->transactionService->getTransactionByUserId($request->user()->id,$transactionBuilder);
        return $this->sendResponse(['transaction'=>$transactionBuilder->paginate()]);

    }
}
