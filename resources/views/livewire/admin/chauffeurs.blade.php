<div class="container py-4">
    <h2 class="mb-4">Gestion des Chauffeurs</h2>

    @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="mb-4">
        <div class="row g-2">
            <div class="col-md-4">
                <input wire:model="name" type="text" class="form-control" placeholder="Nom">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-4">
                <input wire:model="email" type="email" class="form-control" placeholder="Email">
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-4">
                <input wire:model="password" type="password" class="form-control" placeholder="Mot de passe">
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="mt-3">
            <button class="btn btn-primary" type="submit">
                {{ $isEdit ? 'Modifier' : 'Ajouter' }} Chauffeur
            </button>
            @if($isEdit)
            <button class="btn btn-secondary" wire:click="resetForm">Annuler</button>
            @endif
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($chauffeurs as $chauffeur)
            <tr>
                <td>{{ $chauffeur->name }}</td>
                <td>{{ $chauffeur->email }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" wire:click="edit({{ $chauffeur->id }})">Modifier</button>
                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $chauffeur->id }})">Supprimer</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3">Aucun chauffeur enregistré</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
