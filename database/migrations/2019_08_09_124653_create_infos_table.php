<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('icon');
            $table->string('gender', 10);
            $table->string('orientation', 15)->default('Bisexual');
            $table->integer('age')->nullable()->default(0);
            $table->text('about')->nullable()->default(NULL);
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
        Schema::dropIfExists('infos');
    }
}
