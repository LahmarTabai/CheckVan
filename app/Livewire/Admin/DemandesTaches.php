<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Tache;
use App\Models\User;
use App\Models\Vehicule;
use Livewire\WithPagination;
use App\Services\FcmService;
use App\Services\TacheNotificationService;
use Illuminate\Support\Facades\Auth;

class DemandesTaches extends Component
{
    use WithPagination;

    public $search = '';
    public $chauffeurFilter = '';
    public $vehiculeFilter = '';

    public function render()
    {
        $query = Tache::with(['chauffeur', 'vehicule.marque', 'vehicule.modele'])
            ->where('status', 'en_attente')
            ->whereHas('vehicule', function ($q) {
                $q->where('admin_id', Auth::user()->user_id);
            });

        // Filtres
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('chauffeur', function ($subQ) {
                    $subQ->where('nom', 'like', '%' . $this->search . '%')
                         ->orWhere('prenom', 'like', '%' . $this->search . '%');
                })->orWhereHas('vehicule', function ($subQ) {
                    $subQ->where('immatriculation', 'like', '%' . $this->search . '%');
                });
            });
        }

        if ($this->chauffeurFilter) {
            $query->where('chauffeur_id', $this->chauffeurFilter);
        }

        if ($this->vehiculeFilter) {
            $query->where('vehicule_id', $this->vehiculeFilter);
        }

        $query->orderBy('created_at', 'desc');

        return view('livewire.admin.demandes-taches', [
            'demandes' => $query->paginate(10),
            'chauffeurs' => User::where('role', 'chauffeur')
                ->where('admin_id', Auth::user()->user_id)
                ->get(),
            'vehicules' => Vehicule::with(['marque', 'modele'])
                ->where('admin_id', Auth::user()->user_id)
                ->get(),
        ])->layout('layouts.admin');
    }

    public function valider($id)
    {
        $tache = Tache::findOrFail($id);

        // Vérifier que la tâche est en attente
        if ($tache->status !== 'en_attente') {
            session()->flash('error', 'Cette tâche ne peut pas être validée dans son état actuel.');
            return;
        }

        // Mettre à jour le statut et la validation
        $tache->update([
            'status' => 'en_cours',
            'is_validated' => true
        ]);

        // Envoyer une notification FCM au chauffeur
        app(TacheNotificationService::class)->notifyTacheValidated($tache);

        session()->flash('success', 'Tâche validée. Le chauffeur a été notifié.');
    }

    public function rejeter($id)
    {
        $tache = Tache::findOrFail($id);

        // Vérifier que la tâche est en attente
        if ($tache->status !== 'en_attente') {
            session()->flash('error', 'Cette tâche ne peut pas être rejetée dans son état actuel.');
            return;
        }

        // Mettre à jour le statut
        $tache->update([
            'status' => 'rejetee',
            'is_validated' => false
        ]);

        // Envoyer une notification FCM au chauffeur
        app(TacheNotificationService::class)->notifyTacheRejected($tache);

        session()->flash('success', 'Tâche rejetée. Le chauffeur a été notifié.');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->chauffeurFilter = '';
        $this->vehiculeFilter = '';
        $this->resetPage();
    }
}
