<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affectation extends Model
{
    use HasFactory;

    protected $fillable = [
        'chauffeur_id',
        'vehicule_id',
        'status',
    ];

    // 🔁 Une affectation appartient à un chauffeur
    public function chauffeur()
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
    }

    // 🔁 Une affectation appartient à un véhicule
    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    // Affectation.php
    public function photos()
    {
        return $this->hasMany(Photo::class, 'tache_id', 'id'); // ou adapte si tu veux lier à un autre modèle
    }

}
