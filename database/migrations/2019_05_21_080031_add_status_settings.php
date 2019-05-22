<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = ['surveytypes','labs','rooms','floors','products','extents','surface_treatments'];
        
        foreach ($tables as $curtable) {
            Schema::table($curtable, function (Blueprint $table) {
                $table->boolean('active')->default(true);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = ['surveytypes','labs','rooms','floors','products','extents','surface_treatments'];
        
        foreach ($tables as $curtable) {
            Schema::table($curtable, function (Blueprint $table) {
                $table->dropColumn('active');
            });
        }
    }
}
