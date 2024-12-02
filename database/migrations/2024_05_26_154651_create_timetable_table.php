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
        Schema::create('timetable', function (Blueprint $table) {
            $table->id();
            $table->string('nameVenue');
            $table->string('nameLapangan');
            $table->date('days');
            $table->string('availableHour');
            $table->string('unavailableHour');
            $table->unsignedBigInteger('lapanganId');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('lapanganId')->references('id')->on('lapangan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timetable');
    }
};
