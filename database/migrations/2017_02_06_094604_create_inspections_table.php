<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('inspection_number');
            $table->unsignedInteger('survey_id');
            $table->longText('building');
            $table->unsignedInteger('floor_id')->nullable();
            $table->unsignedInteger('room_id')->nullable();
            $table->longText('room_name');
            $table->unsignedInteger('product_id')->nullable();
            $table->string('quantity');
            $table->unsignedInteger('extent_of_damage')->nullable();
            $table->unsignedInteger('surface_treatment')->nullable();
            $table->unsignedInteger('referral')->nullable();
            $table->string('accessibility')->nullable();
            $table->boolean('accessible')->default(true);
            $table->boolean('presumed')->default(false);
            $table->boolean('observation')->default(false);
            $table->boolean('reinspection')->default(false);
            $table->longText('results');
            $table->longText('comments');
            $table->longText('material_location');
            $table->longText('recommendations');
            $table->longText('recommendationsNotes');
            $table->longText('photo');
            $table->string('inspection_date');
            $table->timestamps();
            
            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');
            $table->foreign('floor_id')->references('id')->on('floors')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('extent_of_damage')->references('id')->on('extents')->onDelete('cascade');
            $table->foreign('surface_treatment')->references('id')->on('surface_treatments')->onDelete('cascade');
            $table->foreign('referral')->references('id')->on('inspections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inspections');
    }
}
