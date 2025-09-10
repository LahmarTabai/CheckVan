<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'chemin',
        'nom_fichier',
        'extension',
        'taille',
        'ordre',
        'type'
    ];

    protected $casts = [
        'taille' => 'integer',
        'ordre' => 'integer',
    ];

    // Relations
    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre');
    }

    // Accessors
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->chemin);
    }

    public function getFormattedSizeAttribute()
    {
        if (!$this->taille) return null;

        $bytes = $this->taille;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
