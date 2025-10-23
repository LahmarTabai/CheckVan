<?php

namespace App\Services;

use App\Models\Tache;
use App\Models\User;
use App\Services\FcmService;

class TacheNotificationService
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    /**
     * Envoyer une notification lors de la création d'une tâche
     */
    public function notifyTacheCreated(Tache $tache)
    {
        // Notifier l'admin qu'une nouvelle tâche a été créée
        $admin = User::find($tache->vehicule->admin_id);

        if ($admin && $admin->fcm_token) {
            $this->fcmService->sendToToken(
                $admin->fcm_token,
                'Nouvelle demande de tâche',
                "Le chauffeur {$tache->chauffeur->nom} {$tache->chauffeur->prenom} a demandé une nouvelle tâche.",
                [
                    'type' => 'tache',
                    'tache_id' => $tache->id,
                    'action' => 'created',
                    'chauffeur' => $tache->chauffeur->nom . ' ' . $tache->chauffeur->prenom,
                    'vehicule' => $tache->vehicule->immatriculation
                ]
            );
        }
    }

    /**
     * Envoyer une notification lors de la validation d'une tâche
     */
    public function notifyTacheValidated(Tache $tache)
    {
        $chauffeur = $tache->chauffeur;

        if ($chauffeur && $chauffeur->fcm_token) {
            $this->fcmService->sendToToken(
                $chauffeur->fcm_token,
                'Tâche validée',
                'Votre tâche a été validée par l\'administrateur. Vous pouvez maintenant commencer votre mission.',
                [
                    'type' => 'tache',
                    'tache_id' => $tache->id,
                    'action' => 'validated'
                ]
            );
        }
    }

    /**
     * Envoyer une notification lors du rejet d'une tâche
     */
    public function notifyTacheRejected(Tache $tache)
    {
        $chauffeur = $tache->chauffeur;

        if ($chauffeur && $chauffeur->fcm_token) {
            $this->fcmService->sendToToken(
                $chauffeur->fcm_token,
                'Tâche rejetée',
                'Votre demande de tâche a été rejetée par l\'administrateur.',
                [
                    'type' => 'tache',
                    'tache_id' => $tache->id,
                    'action' => 'rejected'
                ]
            );
        }
    }

    /**
     * Envoyer une notification lors de la fin d'une tâche
     */
    public function notifyTacheCompleted(Tache $tache)
    {
        // Notifier l'admin que la tâche est terminée
        $admin = User::find($tache->vehicule->admin_id);

        if ($admin && $admin->fcm_token) {
            $this->fcmService->sendToToken(
                $admin->fcm_token,
                'Tâche terminée',
                "Le chauffeur {$tache->chauffeur->nom} {$tache->chauffeur->prenom} a terminé sa tâche.",
                [
                    'type' => 'tache',
                    'tache_id' => $tache->id,
                    'action' => 'completed',
                    'chauffeur' => $tache->chauffeur->nom . ' ' . $tache->chauffeur->prenom,
                    'vehicule' => $tache->vehicule->immatriculation
                ]
            );
        }
    }

    /**
     * Envoyer une notification lors de la création d'une affectation
     */
    public function notifyAffectationCreated($affectation)
    {
        $admin = User::find($affectation->vehicule->admin_id);

        if ($admin && $admin->fcm_token) {
            $this->fcmService->sendToToken(
                $admin->fcm_token,
                'Nouvelle prise en charge',
                "Le chauffeur {$affectation->chauffeur->nom} {$affectation->chauffeur->prenom} a pris en charge le véhicule {$affectation->vehicule->immatriculation}.",
                [
                    'type' => 'affectation',
                    'affectation_id' => $affectation->id,
                    'action' => 'created',
                    'chauffeur' => $affectation->chauffeur->nom . ' ' . $affectation->chauffeur->prenom,
                    'vehicule' => $affectation->vehicule->immatriculation
                ]
            );
        }
    }

    /**
     * Envoyer une notification lors de la fin d'une affectation
     */
    public function notifyAffectationTerminated($affectation)
    {
        $admin = User::find($affectation->vehicule->admin_id);

        if ($admin && $admin->fcm_token) {
            $this->fcmService->sendToToken(
                $admin->fcm_token,
                'Véhicule restitué',
                "Le chauffeur {$affectation->chauffeur->nom} {$affectation->chauffeur->prenom} a restitué le véhicule {$affectation->vehicule->immatriculation}.",
                [
                    'type' => 'affectation',
                    'affectation_id' => $affectation->id,
                    'action' => 'terminated',
                    'chauffeur' => $affectation->chauffeur->nom . ' ' . $affectation->chauffeur->prenom,
                    'vehicule' => $affectation->vehicule->immatriculation
                ]
            );
        }
    }

    /**
     * Envoyer une notification lors de l'ajout d'un dommage
     */
    public function notifyDommageAdded($affectation)
    {
        $admin = User::find($affectation->vehicule->admin_id);

        if ($admin && $admin->fcm_token) {
            $this->fcmService->sendToToken(
                $admin->fcm_token,
                'Nouveau dommage signalé',
                "Le chauffeur {$affectation->chauffeur->nom} {$affectation->chauffeur->prenom} a signalé un dommage sur le véhicule {$affectation->vehicule->immatriculation}.",
                [
                    'type' => 'dommage',
                    'affectation_id' => $affectation->id,
                    'action' => 'added',
                    'chauffeur' => $affectation->chauffeur->nom . ' ' . $affectation->chauffeur->prenom,
                    'vehicule' => $affectation->vehicule->immatriculation
                ]
            );
        }
    }
}
