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
            // DROP the wrong/old fields
            if (Schema::hasColumn('avatars', 'name')) $table->dropColumn('name');
            if (Schema::hasColumn('avatars', 'waist_size')) $table->dropColumn('waist_size');
            if (Schema::hasColumn('avatars', 'hip_size')) $table->dropColumn('hip_size');
            if (Schema::hasColumn('avatars', 'avatar_image')) $table->dropColumn('avatar_image');
            if (Schema::hasColumn('avatars', 'image_url')) $table->dropColumn('image_url');
        });
    }

    public function down()
    {
        Schema::table('avatars', function (Blueprint $table) {
            // Optional: re-add dropped columns if rolling back
            $table->string('name')->nullable();
            $table->float('waist_size')->nullable();
            $table->float('hip_size')->nullable();
            $table->string('avatar_image')->nullable();
            $table->string('image_url')->nullable();
        });
    }

};
