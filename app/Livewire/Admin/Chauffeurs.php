<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\ExportService;

class Chauffeurs extends Component
{
    use WithPagination;
    public $nom, $prenom, $email, $password, $chauffeur_id;
    public $tel, $adresse, $date_naissance, $numero_permis, $permis_expire_le, $statut, $date_embauche;
    public $isEdit = false;

    // Filtres
    public $search = '';
    public $filterStatut = '';

    // Tri
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function mount()
    {
        $this->resetForm();
    }

    public function render()
    {
        $query = User::where('role', 'chauffeur')
                     ->where('admin_id', auth()->user()->user_id);

        // Filtres
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                  ->orWhere('prenom', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('tel', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatut) {
            $query->where('statut', $this->filterStatut);
        }

        // Tri
        $query->orderBy($this->sortField, $this->sortDirection);

        $chauffeurs = $query->paginate(10);

        return view('livewire.admin.chauffeurs', [
            'chauffeurs' => $chauffeurs
        ])->layout('layouts.admin');
    }

    public function resetForm()
    {
        $this->nom = '';
        $this->prenom = '';
        $this->email = '';
        $this->password = '';
        $this->tel = '';
        $this->adresse = '';
        $this->date_naissance = '';
        $this->numero_permis = '';
        $this->permis_expire_le = '';
        $this->statut = 'actif';
        $this->date_embauche = '';
        $this->chauffeur_id = null;
        $this->isEdit = false;
    }

    public function save()
    {
        $this->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $this->chauffeur_id . ',user_id',
            'password' => $this->isEdit ? 'nullable' : 'required|min:6',
            'tel' => 'required|string',
            'adresse' => 'required|string',
            'date_naissance' => 'required|date',
            'numero_permis' => 'required|string',
            'permis_expire_le' => 'required|date|after:today',
            'statut' => 'required|in:actif,inactif,suspendu',
            'date_embauche' => 'required|date',
        ]);

        if ($this->isEdit) {
            $user = User::findOrFail($this->chauffeur_id);
            $user->update([
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'email' => $this->email,
                'password' => $this->password ? Hash::make($this->password) : $user->password,
                'tel' => $this->tel,
                'adresse' => $this->adresse,
                'date_naissance' => $this->date_naissance,
                'numero_permis' => $this->numero_permis,
                'permis_expire_le' => $this->permis_expire_le,
                'statut' => $this->statut,
                'date_embauche' => $this->date_embauche,
            ]);
        } else {
            User::create([
                'admin_id' => auth()->user()->user_id,
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => 'chauffeur',
                'tel' => $this->tel,
                'adresse' => $this->adresse,
                'date_naissance' => $this->date_naissance,
                'numero_permis' => $this->numero_permis,
                'permis_expire_le' => $this->permis_expire_le,
                'statut' => $this->statut,
                'date_embauche' => $this->date_embauche,
            ]);
        }

        $this->resetForm();
        session()->flash('success', 'Chauffeur enregistré avec succès');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->chauffeur_id = $user->user_id;
        $this->nom = $user->nom;
        $this->prenom = $user->prenom;
        $this->email = $user->email;
        $this->tel = $user->tel;
        $this->adresse = $user->adresse;
        $this->date_naissance = $user->date_naissance;
        $this->numero_permis = $user->numero_permis;
        $this->permis_expire_le = $user->permis_expire_le;
        $this->statut = $user->statut;
        $this->date_embauche = $user->date_embauche;
        $this->isEdit = true;
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('success', 'Chauffeur supprimé');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatut = '';
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function exportExcel()
    {
        $filters = [
            'search' => $this->search,
            'statut' => $this->filterStatut,
        ];

        return ExportService::exportChauffeurs($filters);
    }
}
