<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPoseToRecommendedProductsTable extends Migration
{
    public function up()
    {
        Schema::table('recommended_products', function (Blueprint $table) {
            $table->string('pose')->default('tpose')->after('product_type');
        });
    }

    public function down()
    {
        Schema::table('recommended_products', function (Blueprint $table) {
            $table->dropColumn('pose');
        });
    }
}
