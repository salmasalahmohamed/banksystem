<?php

namespace App\services;

use App\Data\AccountData;
use App\Data\DepositData;
use App\Data\transactionData;
use App\Data\transferData;
use App\Data\withdrawData;
use App\Events\DepositEvent;
use App\Events\WithdrawlEvent;
use App\Exceptions\AccountNumberExists;
use App\Exceptions\Amounttolow;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidAccountNumberException;
use App\Exceptions\InvalidPinException;
use App\interfaces\AccountServiceInterface;
use App\interfaces\TransferServiceInterface;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountService implements AccountServiceInterface
{
    public userService $userService;
    public TransactionService $transactionService;
    public TransferServiceInterface $transferService;

    public function __construct(userService $userService, TransactionService $transactionService,TransferServiceInterface $transferService)
{
    $this->userService = $userService;
    $this->$transactionService=$transactionService;
    $this-> transferService=$transferService;
}

    public function modelQuery()
    {
        return Account::query();
    }

    /**
     * @throws AccountNumberExists
     */
    public function createAccountNumber($userdata, $id)
    {
        if ($this->userService->hasAccountNumber(Auth::user())){
            throw new AccountNumberExists();
        }
        return $this->modelQuery()->create([
            'account_number'=>substr($userdata,-10),
            'user_id'=>$id
        ]);

    }


    public function getAccountNumberByAccountId($accountnumber)
    {
        // TODO: Implement grtAccountNumberByAccountId() method.
    }

    public function getAccountNumberByUserId($userid)
    {
        $account=$this->modelQuery()->where('user_id',$userid)->first();
        if (!$account){
            throw new \Exception('account not found');
        }
        return $account;
    }

    public function getAccount($accountnumber)
    {
        // TODO: Implement getAccount() method.
    }

    public function deposit(DepositData $depositDto)
    {
        $minimum_deposit = 500;
        if ($depositDto->getAmount() < $minimum_deposit) {
            throw new Amounttolow();
        }
        try {
            DB::beginTransaction();
            $accountquary = $this->modelQuery()->where('account_number', $depositDto->getAccountNumber());
            $this->accountExist($accountquary);
            $lockedAccount = $accountquary->lockForUpdate()->first();
            $accountData = AccountData::fromModel($lockedAccount);
            $transaction = new transactionData();
            $transaction->forDeposit($accountData, $this->transactionService->generateReference(), $depositDto->getAmount(), $depositDto->getDescription());
            event(new DepositEvent($transaction, $accountData, $lockedAccount));
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    /**
     * @throws InvalidAccountNumberException
     */
    private function accountExist(\Illuminate\Database\Eloquent\Builder $accountquary)
    {
        if(!$accountquary->exists()){
            throw new InvalidAccountNumberException();
        }
    }

    /**
     * @throws InvalidPinException
     * @throws InvalidAccountNumberException
     * @throws InsufficientBalanceException
     */
    public function withdraw(withdrawData $withdrawData){
        try {
            DB::beginTransaction();
            $accountquary=$this->modelQuery()->where('account_number',$withdrawData->getAccountNumber());
            $this->accountExist($accountquary);
            $lockedAccount=$accountquary->lockForUpdate()->first();
            $accountData=AccountData::fromModel($lockedAccount);
            if (!$this->userService->validatePin($accountData->getUserId(),$withdrawData->getPin())){
                throw new InvalidPinException();
            }
            $this->canwithdraw($accountData,$withdrawData);
            $transaction= new transactionData();

            $transaction->forWithdrawal($accountData,$this->transactionService->generateReference(),
                $withdrawData);
            event(new WithdrawlEvent($transaction,$accountData,$lockedAccount));
            DB::commit();
            return $transaction;
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @throws InsufficientBalanceException
     */
    public function  canwithdraw(AccountData $accountData, withdrawData $withdrawData){

        if ($accountData->getBalance()<$withdrawData->getAmount()){
            throw new InsufficientBalanceException();
        }
        return true;
    }

    /**
     * @throws \Exception
     */
    public function transfer(string $senderAccountNumber, string $receiverAccountNumber, string $senderAccountPin, int $amount, string $description)
    {
        if ($senderAccountNumber==$receiverAccountNumber){
            throw  new \Exception(' sender and receiver can not be same');
        }
        try {
            DB::beginTransaction();
            $senderAccountQuery=$this->modelQuery()->where('account_number',$senderAccountNumber);
            $receiverAccountQuery=$this->modelQuery()->where('account_number',$receiverAccountNumber);
$this->accountExist($senderAccountQuery);
$this->accountExist($receiverAccountQuery);
            $lockedSenderAccount=$senderAccountQuery->lockForUpdate()->first();
            $lockedReceiverAccount=$receiverAccountQuery->lockForUpdate()->first();
            $accountsenderData=AccountData::fromModel($lockedSenderAccount);
            $accountreceiverData=AccountData::fromModel($lockedReceiverAccount);

            if ($this->userService->validatePin($accountsenderData->getUserId(),$senderAccountPin)){
                throw new InvalidPinException();
            }
            $transaction= new transactionData();
            $withdrawData= $data=new withdrawData();
            $data->setAccountNumber($accountsenderData->getAccountNumber());
            $data->setAmount($amount);
            $data->setDescription($description);
            $data->setPin($senderAccountPin);
            $transactionWithdraw=$transaction->forWithdrawal($accountsenderData,$this->transactionService->generateReference(),
                $withdrawData);
            $this->canwithdraw($accountsenderData,$withdrawData);
            $depositDto=new DepositData();
            $depositDto->setAccountNumber($accountreceiverData->getAccountNumber());
            $depositDto->setAmount($amount);
            $depositDto->setDescription($description);

            $transactionDeposit=$transaction->forDeposit($accountreceiverData,$this->transactionService->generateReference(),$depositDto->getAmount(),$depositDto->getDescription());
$transfer=new transferData();
$transfer->setReference($this->transactionService->generateReference());
$transfer->setSender($accountsenderData->getUserId());
$transfer->setSenderAccountId($accountsenderData->getId());
            $transfer->setRecepientId($accountreceiverData->getId());
            $transfer->setRecepientAccountId($accountreceiverData->getUserId());
            $transfer->setAmount($amount);
            $transfer->setStatus('success');
            $transferData=$this->transferService->createTransfer($transfer);
$transactionWithdraw->setTransfareId($transferData->id);
            $transactionDeposit->setTransfareId($transferData->id);

            event(new WithdrawlEvent($transactionWithdraw,$accountsenderData,$lockedSenderAccount));
            event(new DepositEvent($transactionWithdraw,$accountreceiverData,$lockedReceiverAccount));
            DB::commit();

        }catch (\Exception $exception){
            DB::rollBack();
        }
    }
}
