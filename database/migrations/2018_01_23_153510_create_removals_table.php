<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('removals', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('surveys');            
            $table->longText('project_ref');
            $table->longText('area');
            $table->longText('address');
            $table->longText('prepared_for');
            $table->longText('prepared_by');
            $table->longText('prepared_by_signature_path');
            $table->date('preparation_date');
            $table->longText('approved_by');
            $table->longText('approved_by_signature_path');
            $table->date('approval_date');
            $table->longText('preliminaries');
            $table->longText('site_picture_path');
            $table->longText('map_picture_path');
            $table->longText('floor_plans_path');
            $table->longText('access_routes_path');
            $table->longText('bulk_analysis_certificate_path');
            $table->longText('general_requirements');
            $table->longText('path');
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
        Schema::dropIfExists('removals');
    }
}
