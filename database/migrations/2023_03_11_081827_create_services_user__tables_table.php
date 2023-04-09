<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesUserTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_user__tables', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('category');
            $table->string('service_type');
            $table->string('service_price');
            $table->string('services_suspend');
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
        Schema::dropIfExists('services_user__tables');
    }
}
