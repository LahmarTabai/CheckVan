<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Tache;

class TacheCompleted extends Notification
{
    use Queueable;

    protected $tache;

    public function __construct(Tache $tache)
    {
        $this->tache = $tache;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'tache_completed',
            'tache_id' => $this->tache->id,
            'chauffeur' => $this->tache->chauffeur->nom . ' ' . $this->tache->chauffeur->prenom,
            'vehicule' => $this->tache->vehicule->immatriculation,
            'message' => "Le chauffeur {$this->tache->chauffeur->nom} {$this->tache->chauffeur->prenom} a terminé sa tâche.",
        ];
    }
}
