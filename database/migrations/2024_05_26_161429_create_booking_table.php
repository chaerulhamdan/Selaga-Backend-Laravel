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
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->string('orderName');
            $table->date('date');
            $table->string('hours');
            $table->string('payment');
            $table->unsignedBigInteger('orderId');
            $table->unsignedBigInteger('bookingId');
            $table->string('image');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('orderId')->references('id')->on('users');
            $table->foreign('bookingId')->references('id')->on('timetable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking');
    }
};
