<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_url',
        'product_name',
        'front_image',
        'size_chart_image',
        'price',
        'description',
    ];
    
}
