<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    public function client()
    {
        return $this->hasOne(User::class,'id','client_id');
    }
    public function agency()
    {
        return $this->hasOne(User::class,'id','agency');
    }
    public function chat()
    {
        return $this->hasOne(Chat::class,'project_id','id');
    }
}
