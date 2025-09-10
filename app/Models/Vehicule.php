<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'marque_id',
        'modele_id',
        'immatriculation',
        'type',
        'annee',
        'couleur',
        'kilometrage',
        'statut',
        'description',
        'prix_achat',
        'date_achat',
        'numero_chassis',
        'numero_moteur',
        'derniere_revision',
        'prochaine_revision',
    ];

    protected $casts = [
        'annee' => 'integer',
        'kilometrage' => 'integer',
        'prix_achat' => 'decimal:2',
        'date_achat' => 'date',
        'derniere_revision' => 'date',
        'prochaine_revision' => 'date',
    ];

    // Relations
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }

    public function marque()
    {
        return $this->belongsTo(Marque::class);
    }

    public function modele()
    {
        return $this->belongsTo(Modele::class);
    }

    public function photos()
    {
        return $this->hasMany(VehiculePhoto::class)->orderBy('ordre');
    }

    public function affectations()
    {
        return $this->hasMany(Affectation::class);
    }

    public function taches()
    {
        return $this->hasMany(Tache::class);
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    public function scopeDisponible($query)
    {
        return $query->where('statut', 'disponible');
    }

    // Accessors
    public function getNomCompletAttribute()
    {
        return $this->marque?->nom . ' ' . $this->modele?->nom;
    }

    public function getPhotoPrincipaleAttribute()
    {
        return $this->photos()->first();
    }

    public function getStatutBadgeAttribute()
    {
        $badges = [
            'disponible' => 'success',
            'en_mission' => 'primary',
            'en_maintenance' => 'warning',
            'hors_service' => 'danger'
        ];

        return $badges[$this->statut] ?? 'secondary';
    }
}

