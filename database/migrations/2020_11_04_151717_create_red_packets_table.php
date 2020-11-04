<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedPacketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('red_packets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator_user_id');
            $table->decimal('amount', 12, 2);
            $table->integer('quantity');
            $table->boolean('random');
            $table->decimal('remaining_amount', 12, 2);
            $table->integer('remaining_quantity');
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
        Schema::dropIfExists('red_packets');
    }
}
