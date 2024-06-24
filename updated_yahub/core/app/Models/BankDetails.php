<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetails extends Model
{
    use HasFactory;
    protected $table = 'BankDetails';

    public function bank()
    {
        return $this->hasOne(Bookkeeping::class,'id','bank_id');
    }
    public function category()
    {
        return $this->hasOne(BankCategories::class,'id','category_id');
    }
}
