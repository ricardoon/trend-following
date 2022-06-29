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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('yahoo_code'); // for Yahoo! Finance
            $table->string('category'); //crypto, stock, option, etc.
            $table->integer('price_precision')->default(2); //number of decimal places
            $table->integer('quantity_precision')->default(2); //number of decimal places
            $table->integer('quote_precision')->default(2); //number of decimal places
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['code', 'category']);
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
};
