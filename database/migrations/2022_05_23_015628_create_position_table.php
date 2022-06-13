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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('user_id');
            $table->string('strategy'); // hilo, etc.
            $table->string('side')->nullable(); // buy, sell, etc.
            $table->string('type')->default('market'); // market, limit, etc.
            $table->decimal('price')->nullable(); // limit price for the order
            $table->dateTime('start_datetime')->nullable(); // start time of position
            $table->dateTime('end_datetime')->nullable(); // end time of position
            $table->decimal('max_stop')->nullable(); // max stop to enter
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('asset_id')->references('id')->on('assets');
            $table->foreign('user_id')->references('id')->on('users');
            $table->index('strategy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('position');
    }
};
