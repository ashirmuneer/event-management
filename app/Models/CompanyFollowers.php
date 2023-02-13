<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyFollowers extends Model
{
    use HasFactory;

    public function followeUser(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
