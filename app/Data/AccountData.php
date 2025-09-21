<?php

namespace App\Data;

use App\Models\Account;

class AccountData
{

    private  int $id;
    private int $user_id;
    private string $account_number;
    private  float $balance;

    /**
     * @param string $account_number
     */
    public function setAccountNumber(string $account_number): void
    {
        $this->account_number = $account_number;
    }

    /**
     * @return string
     */
    public function getAccountNumber(): string
    {
        return $this->account_number;
    }
    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @param float $balance
     */
    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

     public static function fromModel(Account $account){
$data=new self();
$data->setId($account->id);
$data->setAccountNumber($account->id);
$data->setBalance($account->balance);
$data->setUserId($account->user_id);
return $data;
     }
}
