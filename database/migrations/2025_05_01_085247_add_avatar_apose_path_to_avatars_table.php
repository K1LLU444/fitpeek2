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
            $table->string('avatar_apose_path')->nullable()->after('avatar_path');
        });
    }

    public function down()
    {
        Schema::table('avatars', function (Blueprint $table) {
            $table->dropColumn('avatar_apose_path');
        });
    }
};
