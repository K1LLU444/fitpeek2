<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'sex', 'bust_size', 'shoulder_width', 'waist_size', 
        'hip_size', 'height', 'weight', 'arm_length', 'leg_length', 'thigh_circumference'
    ];

    // Define the relationship between Avatar and User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
