<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    public function project()
    {
        return $this->hasOne(Project::class,'id','project_id');
    }
    public function agency()
    {
        return $this->hasOne(User::class,'id','agency_id');
    }
    public function client()
    {
        return $this->hasOne(User::class,'id','client_id');
    }
}
