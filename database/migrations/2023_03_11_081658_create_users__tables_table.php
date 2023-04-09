<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users__tables', function (Blueprint $table) {
            $table->id();
            //basic user information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone');
            $table->string('auth_code');
            $table->string('account_balance');
            $table->string('pay_api_code');
            $table->string('profile_photo');
            $table->string('pin_transaction');

            //business information
            $table->string('business_name');
            $table->string('legal_name');
            $table->string('business_profile');
            $table->text('business_address');
            $table->string('proof_address');
            $table->string('kyc_type');
            $table->string('kyc_document');
            $table->text('business_description');
            $table->string('working_days');
            $table->string('head_office');
            $table->string('branch_office');
            $table->text('media_handles');
            
            //account and finance information
            $table->string('account_type');
            $table->string('account_status');
            $table->string('account_name');
            $table->string('bank_name');
            $table->string('account_number');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users__tables');
    }
}
