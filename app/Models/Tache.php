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
        'debut_kilometrage',
        'debut_carburant',
        'end_date',
        'end_latitude',
        'end_longitude',
        'fin_kilometrage',
        'fin_carburant',
        'status',
        'is_validated',
        'description',
        'type_tache',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_validated' => 'boolean',
        'start_latitude' => 'float',
        'start_longitude' => 'float',
        'end_latitude' => 'float',
        'end_longitude' => 'float',
        'debut_kilometrage' => 'integer',
        'fin_kilometrage' => 'integer',
        'debut_carburant' => 'integer',
        'fin_carburant' => 'integer',
    ];

    /**
     * Relation : Tâche → Chauffeur
     */
    public function chauffeur()
    {
        return $this->belongsTo(User::class, 'chauffeur_id', 'user_id');
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
