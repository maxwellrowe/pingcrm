<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Skills extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->string('id');
            $table->string('name');
            $table->string('description');
            $table->string('type_id');
            $table->string('type_name');
            $table->string('category_id');
            $table->string('category_name');
            $table->string('subcategory_id');
            $table->string('subcategory_name');
            $table->boolean('isSoftware');
            $table->boolean('isLanguage');
            $table->string('infoUrl');
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
        Schema::dropIfExists('skills');
    }
}
