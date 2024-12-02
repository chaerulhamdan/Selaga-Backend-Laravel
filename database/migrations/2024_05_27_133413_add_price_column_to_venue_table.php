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
        Schema::table('venue', function (Blueprint $table) {
            //
            $table->string('price', 255)->nullable()->after('fasilitasVenue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('venue', function (Blueprint $table) {
            //
            if(Schema::hasColumn('venue', 'price')) {
                $table->dropColumn('price');
            }
        });
    }
};
