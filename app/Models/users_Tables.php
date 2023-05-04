<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class users_Tables extends Authenticatable
{
    use HasFactory,HasApiTokens,SoftDeletes;

    protected $guarded = [];

    protected static function newFactory(){
    return \Database\Factories\UserTablesFactory::new();
   }
}
