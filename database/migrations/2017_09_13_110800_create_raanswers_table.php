<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRaanswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raanswers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rareport_id');
            $table->unsignedInteger('raquestion_id');
            $table->longText('answer');
            $table->longText('comments');
            $table->longText('recommendation');
            $table->longText('picture');
            $table->unsignedInteger('priority_code');
            $table->longText('action_by_whom');
            $table->longText('actioned_by');
            $table->date('date_of_completion');
            $table->unsignedInteger('info')->default(0);
            $table->timestamps();
            
            $table->foreign('rareport_id')->references('id')->on('rareports')->onDelete('cascade');
            $table->foreign('raquestion_id')->references('id')->on('raquestions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raanswers');
    }
}
