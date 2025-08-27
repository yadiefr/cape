<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaleriFoto extends Model
{
    use HasFactory;
    protected $table = 'galeri_foto';
    protected $fillable = [
        'galeri_id', 'foto', 'is_thumbnail'
    ];

    public function galeri()
    {
        return $this->belongsTo(Galeri::class);
    }
}
