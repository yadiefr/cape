<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo',
        'website',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
