<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    static public function get_setting($key)
    {
        $value=DB::table('settings')->where('key',$key)->first();
        if(!empty($value))
        {
            return $value->value;
        }
        return "";
    }
}
