<?php

namespace App\services;

use App\Data\AccountData;
use App\Data\transferData;
use App\interfaces\TransferServiceInterface;
use App\Models\Transfer;
use Carbon\Carbon;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

class transferService implements TransferServiceInterface
{

    public function modelQuery()
    {
return Transfer::query();
    }

    public function createTransfer(transferData $transferData)
    {
        return $this->modelQuery()->create([
           'sender_id'=>$transferData->getSender(),
            'receiver_id'=>$transferData->getRecepientId(),
            'sender_account_id'=>$transferData->getSenderAccountId(),
            'receiver_account_id'=>$transferData->getRecepientAccountId(),
            'reference'=>$transferData->getReference(),
            'status'=>$transferData->getStatus(),
            'amount'=>$transferData->getAmount(),

        ]);
    }

    public function getTransferBetweenAccount(AccountData $firstaccountData, AccountData $secondaccountData)
    {
        // TODO: Implement getTransferBetweenAccount() method.
    }

    public function generateReference()
    {
        return Str::upper('TF'.'/'.Carbon::now()->getTimestamp().'/'.Str::random(4));
    }

    public function getTransferById($id)
    {
        $transfer=$this->modelQuery()->where('id',$id)->first();
        if (!$transfer){
            throw  new Exception('transfer not found');
        }
        return $transfer;
    }

    public function getTransferByReference($reference)
    {
        $transfer=$this->modelQuery()->where('reference',$reference)->first();
        if (!$transfer){
            throw  new Exception('transfer not found');
        }
        return $transfer;
    }
}
