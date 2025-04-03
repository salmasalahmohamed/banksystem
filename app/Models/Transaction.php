<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    protected $guarded=[];
    public function owner(){
        return $this->belongsTo(User::class);
    }
    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function transfer(){
    return $this->belongsTo(Transfer::class);
}
}
