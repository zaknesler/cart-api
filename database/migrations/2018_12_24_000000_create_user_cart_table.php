<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_cart', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
            $table->integer('product_variation_id')->unsigned()->index();
            $table->integer('quantity')->unsigned()->default(1);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_variation_id')->references('id')->on('product_variations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_cart');
    }
}
