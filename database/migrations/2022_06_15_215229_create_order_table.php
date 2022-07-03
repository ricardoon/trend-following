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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('position_id');
            $table->string('external_id')->nullable(); // external ID of the order. ex: order ID on a exchange.
            $table->string('side'); // buy, sell, etc.
            $table->decimal('entry_price'); // price at which the order was placed.
            $table->integer('quantity'); // quantity of the asset. ex: amount of BTC.
            $table->decimal('size'); // size of the order. ex: amount of USDT.
            $table->decimal('exit_price')->nullable(); // price at which the order was closed.
            $table->dateTime('started_at')->useCurrent(); // start time of order
            $table->dateTime('ended_at')->nullable(); // end time of order
            $table->string('binance_client_order_id')->nullable(); // binance client order ID.
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('position_id')->references('id')->on('positions');
            $table->index('external_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
