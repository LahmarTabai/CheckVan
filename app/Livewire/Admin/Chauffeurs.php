<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\ExportService;

class Chauffeurs extends Component
{
    use WithPagination, WithFileUploads;
    public $nom, $prenom, $email, $password, $password_confirmation, $chauffeur_id;
    public $tel, $adresse, $date_naissance, $numero_permis, $permis_expire_le, $statut, $date_embauche;
    public $profile_picture;
    public $isEdit = false;

    // Filtres
    public $search = '';
    public $filterStatut = '';
    public $filterRole = '';
    public $filterPermis = '';
    public $filterEmailVerified = '';
    public $filterDateEmbaucheDebut = '';
    public $filterDateEmbaucheFin = '';
    public $filterDateNaissanceDebut = '';
    public $filterDateNaissanceFin = '';

    // Modal détails
    public $showDetailsModal = false;
    public $selectedChauffeur = null;

    // Modal suppression
    public $showDeleteModal = false;
    public $chauffeurToDelete = null;

    // Tri
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected function rules()
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->chauffeur_id . ',user_id',
            'tel' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'date_naissance' => 'nullable|date',
            'numero_permis' => 'nullable|string|max:255',
            'permis_expire_le' => 'nullable|date',
            'statut' => 'required|in:actif,inactif,suspendu',
            'date_embauche' => 'nullable|date',
            'password' => $this->isEdit ? 'nullable|min:8' : 'required|min:8',
            'password_confirmation' => 'required_with:password|same:password',
            'profile_picture' => 'nullable|image|max:8192', // 8MB max
        ];
    }

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

        // Filtre par rôle
        if ($this->filterRole) {
            $query->where('role', $this->filterRole);
        }

        // Filtre par email vérifié
        if ($this->filterEmailVerified) {
            if ($this->filterEmailVerified === 'verified') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        // Filtre par date d'embauche (début et fin)
        if ($this->filterDateEmbaucheDebut) {
            $query->where('date_embauche', '>=', $this->filterDateEmbaucheDebut);
        }
        if ($this->filterDateEmbaucheFin) {
            $query->where('date_embauche', '<=', $this->filterDateEmbaucheFin);
        }

        // Filtre par date de naissance (début et fin)
        if ($this->filterDateNaissanceDebut) {
            $query->where('date_naissance', '>=', $this->filterDateNaissanceDebut);
        }
        if ($this->filterDateNaissanceFin) {
            $query->where('date_naissance', '<=', $this->filterDateNaissanceFin);
        }

        // Filtre par permis
        if ($this->filterPermis) {
            $now = now();
            switch ($this->filterPermis) {
                case 'expire_bientot':
                    $query->where('permis_expire_le', '<=', $now->addMonths(1))
                          ->where('permis_expire_le', '>', $now);
                    break;
                case 'expire_dans_3_mois':
                    $query->where('permis_expire_le', '<=', $now->addMonths(3))
                          ->where('permis_expire_le', '>', $now);
                    break;
                case 'expire_dans_6_mois':
                    $query->where('permis_expire_le', '<=', $now->addMonths(6))
                          ->where('permis_expire_le', '>', $now);
                    break;
                case 'expire':
                    $query->where('permis_expire_le', '<', $now);
                    break;
                case 'sans_permis':
                    $query->where(function($q) {
                        $q->whereNull('numero_permis')
                          ->orWhere('numero_permis', '');
                    });
                    break;
            }
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
        $this->password_confirmation = '';
        $this->tel = '';
        $this->adresse = '';
        $this->date_naissance = '';
        $this->numero_permis = '';
        $this->permis_expire_le = '';
        $this->statut = 'actif';
        $this->date_embauche = '';
        $this->profile_picture = null;
        $this->chauffeur_id = null;
        $this->isEdit = false;
    }

    public function save()
    {
        $this->validate();

        // Gérer la photo de profil
        $profilePicturePath = null;
        if ($this->profile_picture) {
            $profilePicturePath = $this->profile_picture->store('profile-pictures', 'public');
        }

        if ($this->isEdit) {
            $user = User::findOrFail($this->chauffeur_id);
            $updateData = [
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
            ];

            if ($profilePicturePath) {
                $updateData['profile_picture'] = $profilePicturePath;
            }

            $user->update($updateData);
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
                'profile_picture' => $profilePicturePath,
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
        $this->password = ''; // Reset du mot de passe
        $this->password_confirmation = ''; // Reset de la confirmation
        $this->profile_picture = null; // Reset pour éviter de garder l'ancienne photo
        $this->isEdit = true;
    }

    public function confirmDelete($id)
    {
        $this->chauffeurToDelete = (int) $id;
        $this->showDeleteModal = true;

    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('success', 'Chauffeur supprimé');
        $this->showDeleteModal = false;
        $this->chauffeurToDelete = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->chauffeurToDelete = null;
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatut = '';
        $this->filterRole = '';
        $this->filterPermis = '';
        $this->filterEmailVerified = '';
        $this->filterDateEmbaucheDebut = '';
        $this->filterDateEmbaucheFin = '';
        $this->filterDateNaissanceDebut = '';
        $this->filterDateNaissanceFin = '';
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
            'role' => $this->filterRole,
            'permis' => $this->filterPermis,
            'email_verified' => $this->filterEmailVerified,
        ];

        return ExportService::exportChauffeurs($filters);
    }

    public function showDetails($id)
    {
        $id = (int) $id;
        $this->selectedChauffeur = User::findOrFail($id);
        $this->showDetailsModal = true;

    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedChauffeur = null;
    }
}
