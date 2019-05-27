<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRareportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rareports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rashop_id');
            $table->integer('revision');
            $table->bigInteger('issue_date');
            $table->longText('comments');
            $table->string('countrylaw')->default("uk");
            $table->unsignedInteger('prepared_by');
            $table->longText('signature');
            $table->longText('risk_level_rate');
            $table->longText('main_picture');
            $table->longText('responsible_person');
            $table->unsignedInteger('assessor');
            $table->longText('person_to_meet');
            $table->longText('use_of_building');
            $table->longText('number_of_floors');
            $table->longText('construction_type');
            $table->integer('max_number_occupants');
            $table->integer('number_employees');
            $table->longText('disabled_occupants');
            $table->longText('remote_occupants');
            $table->longText('hours_operation');
            $table->bigInteger('next_date_recommended');
            $table->longText('executive_summary');
            $table->longText('fire_loss_experience');
            $table->longText('relevant_fire_safety_legislation');
            $table->integer('hazard_from_fire');
            $table->integer('life_safety');
            $table->integer('risk_from_fire');
            $table->integer('general_fire_risk');
            $table->bigInteger('survey_date');
            $table->bigInteger('review_date');
            $table->unsignedInteger('review_by');
            $table->longText('review_signature');
            $table->longText('text_after_review_table');
            $table->longText('competence');
            $table->longText('guidance_used');
            $table->unsignedInteger('completed')->default(0);
            $table->unsignedInteger('email_sent')->default(0);
            $table->timestamps();
            
            $table->foreign('assessor')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rareports');
    }
}
