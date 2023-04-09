<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class users_Tables extends Authenticatable
{
    use HasFactory,HasApiTokens;

    protected $fillable = [
           //personal profile
            'first_name',
            'last_name',
            'email',
            'password',
            'phone',
            'auth_code',
            'account_balance',
            'pay_api_code',
            'profile_photo',
            'pin_transaction',

            //business information
            'business_name',
            'legal_name',
            'business_profile',
            'business_address',
            'proof_address',
            'kyc_type',
            'kyc_document',
            'business_description',
            'working_days',
            'head_office',
            'branch_office',
            'media_handles',
            
            //account and finance information
            'account_type',
            'account_status',
            'account_name',
            'bank_name',
            'account_number',
    ];

    protected $table='users__tables';
}
