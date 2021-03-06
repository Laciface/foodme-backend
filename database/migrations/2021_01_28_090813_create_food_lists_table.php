<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user_from');
            $table->foreign('id_user_from')->references('id')->on('users');
            $table->integer('food_id');
            $table->unique(['id_user_from', 'food_id']);
            $table->string('food_name');
            $table->string('food_photo');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('food_lists');
    }
}
