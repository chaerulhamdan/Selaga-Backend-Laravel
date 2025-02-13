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
        Schema::create('venue', function (Blueprint $table) {
            $table->id();
            $table->string('nameVenue');
            $table->string('lokasiVenue');
            $table->text('descVenue');
            $table->text('fasilitasVenue');
            $table->string('rating');
            $table->string('image');
            $table->unsignedBigInteger('mitraId');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('mitraId')->references('id')->on('mitra');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venue');
    }
};
