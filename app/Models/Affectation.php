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
        'date_debut',
        'date_fin',
        'description',
    ];

    // 🔁 Une affectation appartient à un chauffeur
    public function chauffeur()
    {
        return $this->belongsTo(User::class, 'chauffeur_id', 'user_id');
    }

    // 🔁 Une affectation appartient à un véhicule
    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class, 'affectation_id', 'id');
    }

    /**
     * Relation avec les dommages
     */
    public function dommages()
    {
        return $this->hasMany(Dommage::class);
    }

}
