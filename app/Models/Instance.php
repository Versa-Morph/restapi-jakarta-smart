<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instance extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon'];

    public function details()
    {
        return $this->hasMany(InstanceDetail::class, 'instance_id');
    }
}
