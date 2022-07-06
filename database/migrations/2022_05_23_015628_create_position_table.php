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
            $table->string('granularity'); // 1m, 5m, 15m, 30m, 1h, 2h, 4h, 6h, 8h, 12h, 1d, 3d, 1w, 1M
            $table->decimal('initial_amount', 36, 18); // initial amount of asset to buy/sell
            $table->integer('leverage')->default(1); // leverage of the position. ex: 1x, 2x, 3x, etc.
            $table->decimal('amount', 36, 18)->nullable(); // current amount of asset
            $table->dateTime('started_at')->nullable(); // start time of position
            $table->dateTime('ended_at')->nullable(); // end time of position
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
        Schema::dropIfExists('positions');
    }
};
