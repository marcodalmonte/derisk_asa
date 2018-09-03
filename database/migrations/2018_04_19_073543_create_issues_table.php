<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports_issues', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('survey_id');
            $table->integer('revision');
            $table->longText('authors');
            $table->longText('authors_signatures');
            $table->timestamp('date_completed');
            $table->longText('surveyors');
            $table->longText('surveyors_signatures');
            $table->timestamp('date_checked');
            $table->longText('quality_check');
            $table->longText('quality_signature');
            $table->timestamp('date_authorised');
            $table->timestamp('date_issued');
            $table->longText('issued_to');
            $table->longText('comments');
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
        //
    }
}
