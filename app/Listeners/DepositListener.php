<?php

namespace App\Listeners;

use App\Data\transactionData;
use App\Enum\TransactionCategoryEnum;
use App\Events\DepositEvent;
use App\services\TransactionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DepositListener
{
    /**
     * Create the event listener.
     */
    public function __construct( public  TransactionService $transactionService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DepositEvent $event): void
    {
        if ($event->transaction->getCategory()!=TransactionCategoryEnum::DEPOSIT->value){
            return;
        }

        $this->transactionService->createTransaction($event->transaction);
        $account=$event->lockedAccount;
        $account->balance=$account->balance+$event->transaction->getAmount();
        $account->save();
        $account=$account->refresh();
        $this->transactionService->updateTransactionBalance($event->transaction->getReference(),$account->balance);
        if ($event->transaction->getTransfareId()){
            $this->transactionService->updateTransferId($event->transaction->getReference(),$event->transaction->getTransfareId());

        }


    }
}
