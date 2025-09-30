<?php

namespace App\Listeners;

use App\Enum\TransactionCategoryEnum;
use App\Events\WithdrawlEvent;
use App\services\TransactionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WithdrawalListener
{
    /**
     * Create the event listener.
     */
    public function __construct(public  TransactionService $transactionService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WithdrawlEvent $event): void
    {

        if ($event->transaction->getCategory()!=TransactionCategoryEnum::WITHDRAW->value){

            return;
        }
        $this->transactionService->createTransaction($event->transaction);
        $account=$event->lockedAccount;

        $account->balance=$account->balance-$event->transaction->amount;

        $account->save();
        $account=$account->refresh();

        $this->transactionService->updateTransactionBalance($event->transaction->getReference(),$account->balance);
        if ($event->transaction->getTransfareId()){
            $this->transactionService->updateTransferId($event->transaction->getReference(),$event->transaction->getTransfareId());

        }


    }
}
