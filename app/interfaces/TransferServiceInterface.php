<?php

namespace App\interfaces;

use App\Data\AccountData;
use App\Data\transferData;

interface TransferServiceInterface
{
public function modelQuery();
public function createTransfer(transferData $transferData);
public function getTransferBetweenAccount(AccountData$firstaccountData,AccountData $secondaccountData);
public function generateReference();
public function getTransferById($id);
public function getTransferByReference($reference);
}
