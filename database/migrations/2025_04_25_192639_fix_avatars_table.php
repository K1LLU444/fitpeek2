<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixAvatarsTable extends Migration
{
    public function up()
    {
        Schema::table('avatars', function (Blueprint $table) {
            // Drop all wrong old fields
            if (Schema::hasColumn('avatars', 'bust_size')) $table->dropColumn('bust_size');
            if (Schema::hasColumn('avatars', 'shoulder_width')) $table->dropColumn('shoulder_width');
            if (Schema::hasColumn('avatars', 'thigh_circumference')) $table->dropColumn('thigh_circumference');
            if (Schema::hasColumn('avatars', 'arm_length')) $table->dropColumn('arm_length');
            if (Schema::hasColumn('avatars', 'leg_length')) $table->dropColumn('leg_length');
            // etc. (drop others if needed)

            // Add only correct columns
            if (!Schema::hasColumn('avatars', 'sex')) $table->string('sex')->nullable();
            if (!Schema::hasColumn('avatars', 'height')) $table->float('height')->nullable();
            if (!Schema::hasColumn('avatars', 'weight')) $table->float('weight')->nullable();
            if (!Schema::hasColumn('avatars', 'chest')) $table->float('chest')->nullable();
            if (!Schema::hasColumn('avatars', 'waist')) $table->float('waist')->nullable();
            if (!Schema::hasColumn('avatars', 'hips')) $table->float('hips')->nullable();
            if (!Schema::hasColumn('avatars', 'inseam')) $table->float('inseam')->nullable();
            if (!Schema::hasColumn('avatars', 'avatar_path')) $table->string('avatar_path')->nullable();
        });
    }

    public function down()
    {
        Schema::table('avatars', function (Blueprint $table) {
            // Optional: rollback logic
            $table->dropColumn(['sex', 'height', 'weight', 'chest', 'waist', 'hips', 'inseam', 'avatar_path']);
        });
    }
}
