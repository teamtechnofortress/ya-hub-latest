<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankCategories extends Model
{
    use HasFactory;
    protected $table = 'BankCategories';

    public function bank()
    {
        return $this->hasOne(Bookkeeping::class,'id','bank_id');
    }
}
