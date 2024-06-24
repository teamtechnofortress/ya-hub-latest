<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookkeeping extends Model
{
    use HasFactory;
    protected $table = 'banks';

    public function users()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function categories()
    {
        return $this->hasMany(BankCategories::class,'bank_id','id');
    }

    public function details()
    {
        return $this->hasMany(BankDetails::class,'bank_id','id');
    }
}
