<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer extends Model
{
    use SoftDeletes;

    protected $guarded=[];
    public function sender(){
        return $this->belongsTo(User::class,'sender_id');
    }
    public function receiver(){
        return $this->belongsTo(User::class,'recipient__id');
    }
    public function senderAccount(){
        return $this->belongsTo(Account::class,'sender_account_id','id','id');
    }
    public function receiverAccount(){
        return $this->belongsTo(Account::class,'recipient_account_id','id','id');
    }
}
