<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBio extends Model
{
    use HasFactory;

    protected $table = 'user_bio';

    protected $fillable = [
        'user_id', 'nik', 'profile_pict_path', 'full_name', 'nickname', 'city', 'address', 'age', 'blood_type', 'height', 'weight','phone_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
