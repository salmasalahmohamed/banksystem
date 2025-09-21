<?php

namespace App\Data;

use App\Enum\TransactionCategoryEnum;
use Carbon\Carbon;

class transactionData
{
private  int $id;
private string $reference;
private  int $account_id;
    private  int $transfare_id;

    private  int $user_id;
    private float $amount;
        private float $balance;

    private  string $category;
    private  string $description;
        private  string $meta;

    private Carbon $date;
    private  bool $confirmed;

public function forDeposit(AccountData $accountData, $reference,float $amount,$description){
    $data= new self();
    $data->setUserId($accountData->getUserId());
    $data->setReference($reference);
    $data->setAccountId($accountData->getId());
    $data->setAmount($amount);
    $data->setCategory(TransactionCategoryEnum::DEPOSIT->value);
    $data->setDate(Carbon::now());
    $data->setDescription($description);
    return $data;
}
    public function forWithdrawal(AccountData $accountData, $reference,withdrawData $withdrawData){
        $data= new self();
        $data->setUserId($accountData->getUserId());
        $data->setReference($reference);
        $data->setTransfareId(null);
        $data->setAccountId($accountData->getId());
        $data->setAmount($withdrawData->getAmount());
        $data->setCategory($withdrawData->getCategory());
        $data->setDate(Carbon::now());
        $data->setDescription($withdrawData->getDescription());
        return $data;
    }


/**
 * @return bool
 */public function isConfirmed(): bool
{
    return $this->confirmed;
}/**
 * @param bool $confirmed
 */public function setConfirmed(bool $confirmed): void
{
    $this->confirmed = $confirmed;
}
/**
 * @return Carbon
 */public function getDate(): Carbon
{
    return $this->date;
}/**
 * @param Carbon $date
 */public function setDate(Carbon $date): void
{
    $this->date = $date;
}/**
 * @return string
 */public function getMeta(): string
{
    return $this->meta;
}/**
 * @param string $meta
 */public function setMeta(string $meta): void
{
    $this->meta = $meta;
}/**
 * @return string
 */public function getDescription(): string
{
    return $this->description;
}/**
 * @param string $description
 */public function setDescription(string $description): void
{
    $this->description = $description;
}/**
 * @return string
 */public function getCategory(): string
{
    return $this->category;
}/**
 * @param string $category
 */public function setCategory(string $category): void
{
    $this->category = $category;
}/**
 * @return float
 */public function getBalance(): float
{
    return $this->balance;
}/**
 * @param float $balance
 */public function setBalance(float $balance): void
{
    $this->balance = $balance;
}/**
 * @return float
 */public function getAmount(): float
{
    return $this->amount;
}/**
 * @param float $amount
 */public function setAmount(float $amount): void
{
    $this->amount = $amount;
}/**
 * @return int
 */public function getUserId(): int
{
    return $this->user_id;
}/**
 * @param int $user_id
 */public function setUserId(int $user_id): void
{
    $this->user_id = $user_id;
}/**
 * @return int
 */public function getAccountId(): int
{
    return $this->account_id;
}/**
 * @param int $account_id
 */public function setAccountId(int $account_id): void
{
    $this->account_id = $account_id;
}/**
 * @return string
 */public function getReference(): string
{
    return $this->reference;
}/**
 * @param string $reference
 */public function setReference(string $reference): void
{
    $this->reference = $reference;
}/**
 * @return int
 */public function getId(): int
{
    return $this->id;
}/**
 * @param int $id
 */public function setId(int $id): void
{
    $this->id = $id;
}

    /**
     * @return int
     */
    public function getTransfareId(): int
    {
        return $this->transfare_id;
    }

    /**
     * @param int $transfare_id
     */
    public function setTransfareId(int $transfare_id): void
    {
        $this->transfare_id = $transfare_id;
    }

}
