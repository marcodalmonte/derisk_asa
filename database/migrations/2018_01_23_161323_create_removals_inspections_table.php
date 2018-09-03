<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemovalsInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('removals_inspections', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('area_id');
            $table->longText('room');
            $table->longText('comment');
            $table->longText('inspection_no');    
            $table->longText('result');
            $table->longText('product');
            $table->longText('quantity');
            $table->longText('extent_of_damage');
            $table->longText('surface_treatment');
            $table->longText('recommendation');
            $table->longText('picture_path');
            $table->timestamps();
            
            $table->foreign('area_id')->references('id')->on('removals_areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('removals_inspections');
    }
}
