<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'admin_id',
        'name',
        'email',
        'password',
        'role',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 🔄 Relations

    // Un admin a plusieurs chauffeurs
    public function chauffeurs(): HasMany
    {
        return $this->hasMany(User::class, 'admin_id')->where('role', 'chauffeur');
    }

    // Un chauffeur appartient à un admin
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Si utilisateur est chauffeur → ses affectations
    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class, 'chauffeur_id');
    }

    // Si utilisateur est chauffeur → ses tâches
    public function taches(): HasMany
    {
        return $this->hasMany(Tache::class, 'chauffeur_id');
    }
}
