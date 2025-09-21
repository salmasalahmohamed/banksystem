<?php

namespace App\Data;

class transferData
{
    private ?int $id;
    private int $sender;
    private int $sender_account_id;

    private int $recepient_id;
    private int $recepient_account_id;
    private float|int $amount;
    private string $status;
    private string $reference;
    public function __construct(
        int $sender,
        int $sender_account_id,
        int $recepient_id,
        int $recepient_account_id,
        float|int $amount,
        string $status,
        string $reference
    ) {
        $this->sender              = $sender;
        $this->sender_account_id   = $sender_account_id;
        $this->recepient_id        = $recepient_id;
        $this->recepient_account_id= $recepient_account_id;
        $this->amount              = $amount;
        $this->status              = $status;
        $this->reference           = $reference;
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getSender(): int
    {
        return $this->sender;
    }

    /**
     * @param int $sender
     */
    public function setSender(int $sender): void
    {
        $this->sender = $sender;
    }

    /**
     * @return int
     */
    public function getSenderAccountId(): int
    {
        return $this->sender_account_id;
    }

    /**
     * @param int $sender_account_id
     */
    public function setSenderAccountId(int $sender_account_id): void
    {
        $this->sender_account_id = $sender_account_id;
    }

    /**
     * @return int
     */
    public function getRecepientId(): int
    {
        return $this->recepient_id;
    }

    /**
     * @param int $recepient_id
     */
    public function setRecepientId(int $recepient_id): void
    {
        $this->recepient_id = $recepient_id;
    }

    /**
     * @return int
     */
    public function getRecepientAccountId(): int
    {
        return $this->recepient_account_id;
    }

    /**
     * @param int $recepient_account_id
     */
    public function setRecepientAccountId(int $recepient_account_id): void
    {
        $this->recepient_account_id = $recepient_account_id;
    }

    /**
     * @return float|int
     */
    public function getAmount(): float|int
    {
        return $this->amount;
    }

    /**
     * @param float|int $amount
     */
    public function setAmount(float|int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

}
