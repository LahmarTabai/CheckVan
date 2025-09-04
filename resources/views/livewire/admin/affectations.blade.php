<div class="container py-4">
    <h2 class="mb-4">Gestion des Affectations</h2>

    @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'save' }}">
        <div class="row g-2">
            <div class="col-md-4">
                <select wire:model="chauffeur_id" class="form-control">
                    <option value="">-- Chauffeur --</option>
                    @foreach($chauffeurs as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('chauffeur_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4">
                <select wire:model="vehicule_id" class="form-control">
                    <option value="">-- Véhicule --</option>
                    @foreach($vehicules as $v)
                    <option value="{{ $v->id }}">{{ $v->marque }} - {{ $v->immatriculation }}</option>
                    @endforeach
                </select>
                @error('vehicule_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-2">
                <select wire:model="status" class="form-control">
                    <option value="en_cours">En cours</option>
                    <option value="terminée">Terminée</option>
                </select>
            </div>

            <div class="col-md-2 d-flex">
                <button type="submit" class="btn btn-primary me-2">
                    {{ $isEdit ? 'Modifier' : 'Ajouter' }}
                </button>
                @if ($isEdit)
                <button type="button" wire:click="resetForm" class="btn btn-secondary">Annuler</button>
                @endif
            </div>
        </div>
    </form>

    <hr class="my-4">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Chauffeur</th>
                <th>Véhicule</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($affectations as $a)
            <tr>
                <td>{{ $a->chauffeur->name ?? '-' }}</td>
                <td>{{ $a->vehicule->marque ?? '' }} - {{ $a->vehicule->immatriculation ?? '' }}</td>
                <td>
                    <span class="badge bg-{{ $a->status === 'en_cours' ? 'warning' : 'success' }}">
                        {{ ucfirst($a->status) }}
                    </span>
                </td>
                <td>
                    <button wire:click="edit({{ $a->id }})" class="btn btn-sm btn-warning">Modifier</button>
                    <button wire:click="delete({{ $a->id }})" class="btn btn-sm btn-danger">Supprimer</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Aucune affectation</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
