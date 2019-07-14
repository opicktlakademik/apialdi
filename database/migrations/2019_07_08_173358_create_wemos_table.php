<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wemos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('apikey');
            $table->ipAddress('ip');
            $table->enum('door', [1, 0])->nullable()->default(0);
            $table->enum('lock', [1, 0])->nullable()->default(0);
            $table->enum('power', [1, 0])->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wemos');
    }
}
