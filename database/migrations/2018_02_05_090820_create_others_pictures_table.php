<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOthersPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raothers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rareport_id');
            $table->unsignedInteger('rasection_id');
            $table->longText('picture');
            $table->longText('caption');
            $table->timestamps();
            
            $table->foreign('rareport_id')->references('id')->on('rareports')->onDelete('cascade');
            $table->foreign('rasection_id')->references('id')->on('rasections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raothers');
    }
}
