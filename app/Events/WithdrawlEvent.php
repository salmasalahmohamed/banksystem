<?php

namespace App\Events;

use App\Data\AccountData;
use App\Data\transactionData;
use App\Models\Account;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WithdrawlEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaction;
    public $accountData ;
    public $lockedAccount;

    /**
     * Create a new event instance.
     */
    public function __construct(    transactionData $transaction,   AccountData $accountData,    Account $lockedAccount)
    {
        $this->transaction=$transaction;
        $this->lockedAccount=$lockedAccount;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
