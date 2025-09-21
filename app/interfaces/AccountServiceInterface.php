<?php

namespace App\interfaces;

use App\Data\DepositData;
use App\Data\withdrawData;
use App\Models\Account;
use Monolog\Formatter\WildfireFormatter;

interface AccountServiceInterface
{
public function modelQuery();
public function createAccountNumber($userdata,$id);
public function getAccountNumberByAccountId($accountnumber);
public function getAccountNumberByUserId($userid);
public function getAccount($accountnumber);
public function deposit(DepositData $depositDto);
public function withdraw( withdrawData $withdrawData);
public function  transfer( string $senderAccountNumber, string $receiverAccountNumber, string $senderAccountPin, int $amount, string $description);

}
