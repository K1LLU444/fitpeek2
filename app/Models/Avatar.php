<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sex',
        'height',
        'weight',
        'chest',
        'waist',
        'hips',
        'inseam',
        'unit',
        'avatar_path',
        'avatar_apose_path',
        'avatar_apose_lower_path',                // ✅
        'avatar_front_path',
        'avatar_back_path',
        'avatar_cleaned_front_path',
        'avatar_cleaned_back_path',
        'avatar_front_lower_path',                // ✅
        'avatar_back_lower_path',                 // ✅
        'avatar_cleaned_front_lower_path',        // ✅
        'avatar_cleaned_back_lower_path',         // ✅
    ];    

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
