<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('avatars', function (Blueprint $table) {
            $table->string('unit', 5)->default('cm')->after('inseam');
        });
    }
    
    public function down()
    {
        Schema::table('avatars', function (Blueprint $table) {
            $table->dropColumn('unit');
        });
    }    
};
