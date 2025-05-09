<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendedProduct extends Model
{
    protected $table = 'recommended_products';

    protected $fillable = [
        'product_name',
        'product_link',
        'price',
        'description',
        'size_chart',
        'tag_styles',
        'tag_events',
        'tag_weather',
        'sex',
        'front_image',
        'back_image',
        'product_type',
    ];

    protected $casts = [
        'tag_styles' => 'array',
        'tag_events' => 'array',
        'tag_weather' => 'array',
        'size_chart' => 'array',
    ];
}
