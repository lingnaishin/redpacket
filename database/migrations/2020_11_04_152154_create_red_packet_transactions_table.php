<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedPacketTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('red_packet_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('target_red_packet_id');
            $table->integer('receiver_user_id');
            $table->decimal('received_amount', 12, 2);
            $table->boolean('success')->nullable();
            $table->string('unsuccessful_reason')->nullable();
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
        Schema::dropIfExists('red_packet_transactions');
    }
}
