<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'chauffeur_id', 'latitude', 'longitude', 'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function chauffeur()
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
    }
}



