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
            $table->string('avatar_cleaned_front_lower_path')->nullable()->after('avatar_cleaned_back_path');
            $table->string('avatar_cleaned_back_lower_path')->nullable()->after('avatar_cleaned_front_lower_path');
        });
    }
    
    public function down()
    {
        Schema::table('avatars', function (Blueprint $table) {
            $table->dropColumn([
                'avatar_cleaned_front_lower_path',
                'avatar_cleaned_back_lower_path',
            ]);
        });
    }    
};
