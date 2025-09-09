<div class="container py-4">
    <h2 class="mb-4">Gestion des Tâches</h2>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filtres --}}
    <div class="row mb-3 g-2">
        <div class="col-md-3">
            <select wire:model="chauffeurFilter" class="form-select">
                <option value="">Tous les chauffeurs</option>
                @foreach ($chauffeurs as $chauffeur)
                    <option value="{{ $chauffeur->id }}">{{ $chauffeur->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select wire:model="vehiculeFilter" class="form-select">
                <option value="">Tous les véhicules</option>
                @foreach ($vehicules as $vehicule)
                    <option value="{{ $vehicule->id }}">{{ $vehicule->immatriculation }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select wire:model="statusFilter" class="form-select">
                <option value="">Tous les statuts</option>
                <option value="en_attente">En attente</option>
                <option value="en_cours">En cours</option>
                <option value="terminée">Terminée</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-center">
            <form method="GET" action="{{ route('admin.export.taches') }}" class="d-flex align-items-center w-100">
                <select name="chauffeur_id" class="form-select me-2" required>
                    <option value="">Chauffeur</option>
                    @foreach ($chauffeurs as $chauffeur)
                        <option value="{{ $chauffeur->id }}">{{ $chauffeur->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="year" class="form-control me-2" placeholder="Année" min="2000"
                    value="{{ now()->year }}" required>
                <input type="number" name="month" class="form-control me-2" placeholder="Mois" min="1"
                    max="12" value="{{ now()->month }}" required>
                <button type="submit" class="btn btn-outline-primary">Exporter</button>
            </form>
        </div>
    </div>

    {{-- Formulaire de création / édition --}}
    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'create' }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-4">
                <select wire:model="chauffeur_id" class="form-select">
                    <option value="">Sélectionner un chauffeur</option>
                    @foreach ($chauffeurs as $chauffeur)
                        <option value="{{ $chauffeur->id }}">{{ $chauffeur->name }}</option>
                    @endforeach
                </select>
                @error('chauffeur_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4">
                <select wire:model="vehicule_id" class="form-select">
                    <option value="">Sélectionner un véhicule</option>
                    @foreach ($vehicules as $vehicule)
                        <option value="{{ $vehicule->id }}">{{ $vehicule->immatriculation }}</option>
                    @endforeach
                </select>
                @error('vehicule_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4">
                <input type="datetime-local" wire:model="start_date" class="form-control">
                @error('start_date')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary" type="submit">
                {{ $isEdit ? 'Mettre à jour' : 'Créer' }} la tâche
            </button>
            @if ($isEdit)
                <button type="button" wire:click="resetForm" class="btn btn-secondary">Annuler</button>
            @endif
        </div>
    </form>

    {{-- Liste des tâches --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Chauffeur</th>
                <th>Véhicule</th>
                <th>Début</th>
                <th>Statut</th>
                <th>Validée</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($taches as $tache)
                <tr>
                    <td>{{ $tache->chauffeur->name ?? '-' }}</td>
                    <td>{{ $tache->vehicule->immatriculation ?? '-' }}</td>
                    <td>{{ $tache->start_date }}</td>
                    <td>{{ ucfirst($tache->status) }}</td>
                    <td>
                        @if ($tache->is_validated)
                            <span class="badge bg-success">Oui</span>
                        @else
                            <span class="badge bg-secondary">Non</span>
                        @endif
                    </td>
                    <td>
                        @if (!$tache->is_validated)
                            <button class="btn btn-sm btn-success"
                                wire:click="valider({{ $tache->id }})">Valider</button>
                        @endif
                        <button class="btn btn-sm btn-warning" wire:click="edit({{ $tache->id }})">Modifier</button>
                        <button class="btn btn-sm btn-danger"
                            wire:click="delete({{ $tache->id }})">Supprimer</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Aucune tâche</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $taches->links() }}
    </div>
</div>
