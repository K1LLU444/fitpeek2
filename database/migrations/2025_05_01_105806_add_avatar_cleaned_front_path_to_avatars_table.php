<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvatarCleanedFrontPathToAvatarsTable extends Migration
{
    public function up(): void
    {
        Schema::table('avatars', function (Blueprint $table) {
            $table->string('avatar_cleaned_front_path')->nullable()->after('avatar_front_path');
        });
    }

    public function down(): void
    {
        Schema::table('avatars', function (Blueprint $table) {
            $table->dropColumn('avatar_cleaned_front_path');
        });
    }
}
