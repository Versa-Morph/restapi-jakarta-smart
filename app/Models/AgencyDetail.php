<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyDetail extends Model
{
    use HasFactory;

    protected $fillable = ['agency_id', 'name', 'logo', 'address', 'longitude', 'latitude'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
