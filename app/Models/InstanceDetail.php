<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstanceDetail extends Model
{
    use HasFactory;

    protected $table = 'instance_details';

    protected $fillable = ['instance_id', 'name', 'logo', 'address', 'longitude', 'latitude'];

    public function instance()
    {
        return $this->belongsTo(Instance::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
