<?php

namespace App\services;

use App\Exceptions\AccountNumberExists;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class userService
{

    public  function register($data ){

            $user = User::query()->create($data);
            return $user;

    }
    public function getUserById($user_id){
        $user=User::query()->where('id',$user_id)->first();
        if(!$user_id){
            throw new ModelNotFoundException('User not found by ID ' . $user_id);
        }
        return $user;
    }
    public function setUpPin(User $user,$pin){
        if(strlen($pin)!=4){
            throw new ValidationException();
        }
        if( $this->hasSetPin($user)){
            throw new  AccountNumberExists();
        }
        $user->pin=Hash::make($pin);
$user->save();
    }
    public function validatePin($user_id,$pin): bool
    {
        $user=$this->getUserById($user_id);
        if( !$this->hasSetPin($user)){
            throw new  BadRequestException('set ur pin');
        }
        return Hash::check($pin,$user->pin);
    }
    public function hasSetPin(User $user){
        return $user->pin !=null;
    }
    public function hasAccountNumber(User  $user)
    {
        return $user->account()->exists();

    }
}
