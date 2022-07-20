<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hilos', function (Blueprint $table) {
            $table->unsignedBigInteger('asset_id');
            $table->integer('length');
            $table->string('granularity'); // 1d, 1h, 1m, 1s
            $table->dateTime('last_check_at'); // last time the hilo was checked for new data
            $table->string('last_action')->nullable(); // last action suggested on the hilo. ex: buy, sell.
            $table->timestamps();

            $table->foreign('asset_id')->references('id')->on('assets');
            $table->unique(['asset_id', 'granularity']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hilos');
    }
};
