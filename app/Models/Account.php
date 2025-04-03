<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;
    protected $guarded=[];
    public function owner(){
        return $this->belongsTo(User::class);
    }
    public function transation(){
        return $this->hasMany(Transaction::class);
    }
}
