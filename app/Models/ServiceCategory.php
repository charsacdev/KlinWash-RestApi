<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    #hanldes factories
    protected static function newFactory(){
        return \Database\Factories\ServiceCategoryFactory::new();
    }
}
