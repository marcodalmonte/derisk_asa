<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemovalsAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('removals_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('removal_id');
            $table->longText('building');
            $table->longText('name');
            $table->longText('text');            
            $table->timestamps();
            
            $table->foreign('removal_id')->references('id')->on('removals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('removals_areas');
    }
}
