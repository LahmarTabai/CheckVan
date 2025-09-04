<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'immatriculation',
        'marque',
        'modele',
        'photo',
        'admin_id',
    ];


    // 🔁 Un véhicule appartient à un administrateur
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // 🔁 Un véhicule peut avoir plusieurs affectations
    public function affectations()
    {
        return $this->hasMany(Affectation::class);
    }

    // 🔁 Un véhicule peut avoir plusieurs tâches
    public function taches()
    {
        return $this->hasMany(Tache::class);
    }

    
}

