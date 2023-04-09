<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications__tables', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('message_type');
            $table->string('message_title');
            $table->text('message_body');
            $table->string('message_read');
            $table->string('message_date');
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
        Schema::dropIfExists('notifications__tables');
    }
}
