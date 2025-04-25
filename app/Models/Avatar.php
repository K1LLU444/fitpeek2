<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'sex', 'height', 'weight', 'chest', 'waist', 'hips', 'inseam', 'avatar_path'
    ];    
    

    // Define the relationship between Avatar and User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
