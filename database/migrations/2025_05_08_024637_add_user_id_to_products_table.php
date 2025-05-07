<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
{
    // ✅ Fix invalid NULLs to safe default user ID (adjust if needed)
    \DB::table('products')->whereNull('user_id')->update(['user_id' => 1]);

    Schema::table('products', function (Blueprint $table) {
        // ✅ Remove this line if column already exists
        // $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // ✅ Instead, just attach the foreign key constraint
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
    });
}

    

};
