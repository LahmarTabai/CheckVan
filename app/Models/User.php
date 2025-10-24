<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'admin_id',
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'profile_picture',
        'tel',
        'adresse',
        'date_naissance',
        'numero_permis',
        'permis_expire_le',
        'statut',
        'date_embauche',
        'fcm_token',
        'is_online',
        'last_seen',
        'last_heartbeat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen' => 'datetime',
        'last_heartbeat' => 'datetime',
    ];

    // 🔄 Relations

    // Un admin a plusieurs chauffeurs
    public function chauffeurs(): HasMany
    {
        return $this->hasMany(User::class, 'admin_id', 'user_id')->where('role', 'chauffeur');
    }

    // Un chauffeur appartient à un admin
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }

    // Si utilisateur est chauffeur → ses affectations
    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class, 'chauffeur_id', 'user_id');
    }

    // Si utilisateur est chauffeur → ses tâches
    public function taches(): HasMany
    {
        return $this->hasMany(Tache::class, 'chauffeur_id', 'user_id');
    }

    // Dernière position GPS du chauffeur (optimisé pour éviter N+1)
    public function lastLocation()
    {
        return $this->hasOne(Location::class, 'chauffeur_id', 'user_id')
                    ->latestOfMany('recorded_at');
    }

    // Tâche en cours du chauffeur (optimisé)
    public function currentTache()
    {
        return $this->hasOne(Tache::class, 'chauffeur_id', 'user_id')
                    ->where('status', 'en_cours')
                    ->latestOfMany();
    }
}
