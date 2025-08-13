<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'link',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean'
    ];

    public function user()
    {
        // Update this relationship to point to the correct model/table if needed, or remove if not used
        // return $this->belongsTo(User::class);
        // If notifications are for Guru, Admin, or Siswa, you may need to implement polymorphic or separate relationships
        // For now, comment out to avoid errors
        return null;
    }
}