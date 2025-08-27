<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolBell extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'day_of_week',
        'time',
        'sound_file',
        'description',
        'is_active',
        'type',
        'color_code',
        'icon'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'time' => 'datetime:H:i',
    ];

    /**
     * Scope untuk mendapatkan bel yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk bel pada hari tertentu
     */
    public function scopeForDay($query, $day)
    {
        return $query->where('day_of_week', $day)->orWhereNull('day_of_week');
    }

    /**
     * Scope untuk mendapatkan bel berdasarkan tipe
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
