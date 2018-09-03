<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRaquestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raquestions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rasection_id');
            $table->longtext('question');
            $table->string('goal');            
            $table->timestamps();
            
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
        Schema::dropIfExists('raquestions');
    }
}
