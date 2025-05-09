<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTagsInWeatherFilteredProductsTable extends Migration
{
    public function up()
    {
        Schema::table('recommended_products', function (Blueprint $table) {
            $table->renameColumn('tags', 'tag_styles');
            $table->json('tag_events')->nullable()->after('tag_styles');
            $table->json('tag_weather')->nullable()->after('tag_events');
        });
    }

    public function down()
    {
        Schema::table('recommended_products', function (Blueprint $table) {
            $table->renameColumn('tag_styles', 'tags');
            $table->dropColumn('tag_events');
            $table->dropColumn('tag_weather');
        });
    }
}
