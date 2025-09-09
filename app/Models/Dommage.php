<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dommage extends Model
{
    protected $fillable = [
        'affectation_id',
        'chauffeur_id',
        'coord_x',
        'coord_y',
        'coord_z',
        'type',
        'description',
        'severite',
        'photo_path',
        'reparé'
    ];

    protected $casts = [
        'coord_x' => 'decimal:2',
        'coord_y' => 'decimal:2',
        'coord_z' => 'decimal:2',
        'reparé' => 'boolean',
    ];

    /**
     * Relation avec l'affectation
     */
    public function affectation(): BelongsTo
    {
        return $this->belongsTo(Affectation::class);
    }

    /**
     * Relation avec le chauffeur
     */
    public function chauffeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
    }

    /**
     * Scope pour les dommages non réparés
     */
    public function scopeNonRepares($query)
    {
        return $query->where('reparé', false);
    }

    /**
     * Scope par type de dommage
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope par sévérité
     */
    public function scopeParSeverite($query, $severite)
    {
        return $query->where('severite', $severite);
    }
}
