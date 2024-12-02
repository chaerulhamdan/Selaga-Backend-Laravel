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
        Schema::create('lapangan', function (Blueprint $table) {
            $table->id();
            $table->string('nameLapangan');
            $table->date('days');
            $table->string('hour');
            $table->unsignedBigInteger('venueId');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('venueId')->references('id')->on('venue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lapangan');
    }
};
