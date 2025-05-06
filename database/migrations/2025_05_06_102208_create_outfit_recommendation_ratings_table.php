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
        Schema::create('outfit_recommendation_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('gpt_response_hash'); // md5 hash of GPT reply
            $table->tinyInteger('rating'); // 1 to 5 stars
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('outfit_recommendation_ratings');
    }    
};
