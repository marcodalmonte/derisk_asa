<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRaReadyCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rareadycomments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('raquestion_id');
            $table->longText('text');
            $table->unsignedInteger('client_id');
            $table->timestamps();
            
            $table->foreign('raquestion_id')->references('id')->on('raquestions')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rareadycomments');
    }
}
