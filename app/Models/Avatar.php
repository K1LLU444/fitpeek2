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
        'avatar_path',          // ✅ t-pose .obj
        'avatar_apose_path',    // ✅ a-pose .obj
        'avatar_front_path',    // ✅ screenshot (front)
        'avatar_back_path',     // ✅ screenshot (back)
        'avatar_cleaned_front_path',
        'avatar_cleaned_back_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
