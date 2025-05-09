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
    Schema::create('avatars', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('sex');
        $table->decimal('bust_size', 5, 2);
        $table->decimal('shoulder_width', 5, 2);
        $table->decimal('waist_size', 5, 2);
        $table->decimal('hip_size', 5, 2);
        $table->decimal('height', 5, 2);
        $table->decimal('weight', 5, 2);
        $table->decimal('arm_length', 5, 2);
        $table->decimal('leg_length', 5, 2);
        $table->decimal('thigh_circumference', 5, 2);
        $table->timestamps();
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avatars');
    }
};
