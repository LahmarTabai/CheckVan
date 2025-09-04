<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    use HasFactory;

    protected $fillable = [
        'chauffeur_id',
        'vehicule_id',
        'start_date',
        'start_latitude',
        'start_longitude',
        'end_date',
        'end_latitude',
        'end_longitude',
        'status',
    ];

    /**
     * Relation : Tâche → Chauffeur
     */
    public function chauffeur()
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
    }

    /**
     * Relation : Tâche → Véhicule
     */
    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    /**
     * Relation : Tâche → Photos
     */
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
