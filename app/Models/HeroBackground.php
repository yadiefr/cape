<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroBackground extends Model
{
    protected $fillable = [
        'image',
        'opacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'opacity' => 'float',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
