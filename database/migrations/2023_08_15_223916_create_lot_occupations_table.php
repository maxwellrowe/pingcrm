<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotOccupationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lot_occupations', function (Blueprint $table) {
            $table->id();
            $table->string('lot_id');
            $table->string('name');
            $table->string('level');
            $table->string('level_name');
            $table->string('dimension');
            $table->string('description_us');
            $table->string('parent_id');
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
        Schema::dropIfExists('lot_occupations');
    }
}
