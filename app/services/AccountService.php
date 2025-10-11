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
    public transferService $transferService;

    public function __construct(userService $userService, TransactionService $transactionService,transferService $transferService)
{
    $this->userService = $userService;
    $this->transactionService=$transactionService;
    $this-> transferService=new transferService();
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
            'balance'=>$userdata,
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
            $accountquary = $this->modelQuery()->where('id', $depositDto->getAccountNumber());
            $this->accountExist($accountquary);
            $lockedAccount = $accountquary->lockForUpdate()->first();
            $accountData = AccountData::fromModel($lockedAccount);
            $transaction = new transactionData($accountData->getAccountNumber(),$accountData->getUserId(),$depositDto->getAmount(),$this->transactionService->generateReference(),'deposit',$depositDto->getDescription());

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


    /**
     * @throws InvalidPinException
     * @throws InvalidAccountNumberException
     * @throws InsufficientBalanceException
     */
    public function withdraw(withdrawData $withdrawData){
        try {
            DB::beginTransaction();
            $accountquary=$this->modelQuery()->where('id',$withdrawData->getAccountNumber());
            $this->accountExist($accountquary);
            $lockedAccount=$accountquary->lockForUpdate()->first();
            $accountData=AccountData::fromModel($lockedAccount);
            if (!$this->userService->validatePin($accountData->getUserId(),$withdrawData->getPin())){

                throw new InvalidPinException();
            }

            $this->canwithdraw($accountData,$withdrawData);
            $transaction = new transactionData($accountData->getAccountNumber(),$accountData->getUserId(),$withdrawData->getAmount(),$this->transactionService->generateReference(),'withdraw');


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
    private function accountExist(\Illuminate\Database\Eloquent\Builder $accountquary)
    {

        if(!$accountquary->exists()){
            throw new InvalidAccountNumberException();
        }

        return true;
    }
    /**
     * @throws \Exception
     */
    public function transfer( $senderAccountNumber,  $receiverAccountNumber, string $senderAccountPin, int $amount, string $description)
    {

        if ($senderAccountNumber==$receiverAccountNumber){
            throw  new \Exception(' sender and receiver can not be same');
        }
        try {

            DB::beginTransaction();

            $senderAccountQuery=$this->modelQuery()->where('id',$senderAccountNumber);
            $receiverAccountQuery=$this->modelQuery()->where('id',$receiverAccountNumber);

            $this->accountExist($senderAccountQuery);

            $this->accountExist($receiverAccountQuery);
$lockedSenderAccount=$senderAccountQuery->lockForUpdate()->first();
            $lockedReceiverAccount=$receiverAccountQuery->lockForUpdate()->first();

            $accountsenderData=AccountData::fromModel($lockedSenderAccount);
            $accountreceiverData=AccountData::fromModel($lockedReceiverAccount);

            if (!$this->userService->validatePin($accountsenderData->getUserId(),$senderAccountPin)){
                throw new InvalidPinException();
            }

            $withdrawData= $data=new withdrawData();
            $data->setAccountNumber($accountsenderData->getAccountNumber());
            $data->setAmount($amount);
            $data->setDescription($description);
            $data->setPin($senderAccountPin);

             $transactionWithdraw = new transactionData($accountsenderData->getAccountNumber(),$accountsenderData->getUserId(),$withdrawData->getAmount(),$this->transactionService->generateReference(),'withdraw');


            $this->canwithdraw($accountsenderData,$withdrawData);
            $depositDto=new DepositData();

            $depositDto->setAccountNumber($accountreceiverData->getAccountNumber());
            $depositDto->setAmount($amount);
            $depositDto->setDescription($description);
            $transactionDeposit = new transactionData($accountreceiverData->getAccountNumber(),$accountreceiverData->getUserId(),$depositDto->getAmount(),$this->transactionService->generateReference(),'deposit',$depositDto->getDescription());

            $transfer=new transferData($accountsenderData->getUserId(),$accountsenderData->getAccountNumber(),$accountreceiverData->getUserId(),$accountreceiverData->getAccountNumber(),$amount,'status',$this->transactionService->generateReference());

            $transferData=$this->transferService->createTransfer($transfer);

            $transactionWithdraw->setTransfareId($transferData->id);
            $transactionDeposit->setTransfareId($transferData->id);
            event(new WithdrawlEvent($transactionWithdraw,$accountsenderData,$lockedSenderAccount));
            event(new DepositEvent($transactionDeposit,$accountreceiverData,$lockedReceiverAccount));
            DB::commit();
return true;
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;

        }
    }
}
