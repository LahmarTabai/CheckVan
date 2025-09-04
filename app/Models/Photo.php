<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'tache_id',
        'type',
        'path',
    ];

    /**
     * Relation : Photo → Tâche
     */
    public function tache()
    {
        return $this->belongsTo(Tache::class);
    }
}
