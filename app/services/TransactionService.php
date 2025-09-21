<?php

namespace App\services;

use App\Enum\TransactionCategoryEnum;
use App\Models\Transaction;
use App\Models\Transfer;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

class TransactionService
{

    public function modelQuery()
    {
        return Transaction::query();
    }
public function generateReference(){
    return Str::upper('TF'.'/'.Carbon::now()->getTimestamp().'/'.Str::random(4));
}

    public function createTransaction(\App\Data\transactionData $transactiondata)
    {
        return Transaction::query()->create([
            'user_id'=>$transactiondata->getUserId(),
           'account_id'=>$transactiondata->getAccountId(),
            'reference'=>$transactiondata->getReference(),
            'category'=>$transactiondata->getCategory(),
            'date'=>$transactiondata->getDate(),
            'description'=>$transactiondata->getDescription(),

        ]);
    }


    public function updateTransactionBalance($reference,$balance)
    {
        Transaction::query()->where('reference',$reference)->update([
         'balance'=>$balance,
            'confirmed'=>true,
        ]) ;
    }
    public function updateTransferId($reference,$id)
    {
        Transaction::query()->where('reference',$reference)->update([
            'transfer_id'=>$id,
        ]) ;
    }
    public function getTransactionByUserId($userId,Builder $builder){
        return  $builder->where('user_id',$userId);

    }
    public function getTransactionById($transactionId){
        $transaction= Transaction::query()->where('id',$transactionId)->get();
        if (!$transaction){
            throw  new Exception('no transaction');
        }
        return$transaction;
    }
    public function getTransactionByReference($reference){
 $transaction= Transaction::query()->where('reference',$reference)->get();
 if (!$transaction){
     throw  new Exception('no transaction');
 }
 return $transaction;
    }
    public function getTransactionByAccountNumber($account_number,Builder $builder){
        return $transaction= $builder->whereHas('account',function ($q) use($account_number)
        {
               $q->where('account_number',$account_number);
            });


    }
    public function downTransactionHistory(){

}
}
