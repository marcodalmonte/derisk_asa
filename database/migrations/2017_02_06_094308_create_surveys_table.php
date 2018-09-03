<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('jobnumber')->unique();
            $table->string('ukasnumber')->unique();
            $table->unsignedInteger('surveytype_id');
            $table->unsignedInteger('client_id');
            $table->date('surveydate');
            $table->longText('othersdates')->nullable();
            $table->string('reinspectionof');
            $table->longText('siteaddress');
            $table->longText('sitedescription');
            $table->longText('scope');
            $table->longText('agreed_excluded_areas');
            $table->longText('deviations_from_standard_methods');
            $table->unsignedInteger('lab_id');
            $table->string('issued_to');
            $table->string('urgency')->default("Standard");
            $table->timestamps();
            
            $table->foreign('surveytype_id')->references('id')->on('surveytypes')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surveys');
    }
}
