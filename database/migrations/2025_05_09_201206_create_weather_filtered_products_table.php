<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherFilteredProductsTable extends Migration
{
    public function up(): void
    {
        Schema::create('recommended_products', function (Blueprint $table) {
            $table->id();

            $table->string('product_name');
            $table->string('product_link')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->text('description')->nullable();

            $table->json('size_chart')->nullable(); // Example: {"S":{"width":19,"length":26}, "M":{...}}
            $table->json('tags')->nullable(); // Example: ["sunny", "sporty", "festival"]
            $table->enum('sex', ['male', 'female', 'unisex'])->default('unisex');

            $table->string('front_image')->nullable(); // URL or storage path
            $table->string('back_image')->nullable();

            $table->enum('product_type', ['top', 'bottom', 'accessory'])->default('top');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommended_products');
    }
}
