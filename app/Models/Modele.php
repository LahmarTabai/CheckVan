<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modele extends Model
{
    use HasFactory;

    protected $fillable = [
        'marque_id',
        'nom',
        'type_vehicule',
        'annee_debut',
        'annee_fin',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'annee_debut' => 'integer',
        'annee_fin' => 'integer',
    ];

    // Relations
    public function marque()
    {
        return $this->belongsTo(Marque::class);
    }

    public function vehicules()
    {
        return $this->hasMany(Vehicule::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByMarque($query, $marqueId)
    {
        return $query->where('marque_id', $marqueId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type_vehicule', $type);
    }
}
