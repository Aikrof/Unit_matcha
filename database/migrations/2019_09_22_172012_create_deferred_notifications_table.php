<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeferredNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deferred_notifications', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('to_id');
            $table->bigInteger('from_id');
            $table->String('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deferred_notifications');
    }
}
