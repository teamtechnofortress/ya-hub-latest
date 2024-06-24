<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'role',
        'email',
        'password',
        'is_active',
        'secret_code',
        'notification_status',
        'assigned_clients',
        'agency_name',
        'theme_setting',
        'theme_style',
        'theme_log'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function agencyProject()
    {
        return $this->hasOne(Project::class,'agency_id','id');
    }
    public function projectByMe()
    {
        return $this->hasOne(Chat::class,'agency_id','id');
    }
    public function projectToMe()
    {
        return $this->hasOne(Chat::class,'client_id','id');
    }

    public function projects(){
        return $this->hasMany(Project::class);
    }
}
