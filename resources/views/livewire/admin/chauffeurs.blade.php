<div class="container py-4">
    <h2 class="mb-4">Gestion des Chauffeurs</h2>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="mb-4">
        <div class="row g-3">
            <!-- Informations de base -->
            <div class="col-md-3">
                <label class="form-label">Nom *</label>
                <input wire:model="nom" type="text" class="form-control" placeholder="Nom">
                @error('nom')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Prénom *</label>
                <input wire:model="prenom" type="text" class="form-control" placeholder="Prénom">
                @error('prenom')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Email *</label>
                <input wire:model="email" type="email" class="form-control" placeholder="Email">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-2">
                <label class="form-label">Téléphone *</label>
                <input wire:model="tel" type="tel" class="form-control" placeholder="Téléphone">
                @error('tel')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Mot de passe -->
            <div class="col-md-4">
                <label class="form-label">Mot de passe
                    {{ $isEdit ? '(laisser vide pour ne pas changer)' : '*' }}</label>
                <input wire:model="password" type="password" class="form-control" placeholder="Mot de passe">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Adresse -->
            <div class="col-md-8">
                <label class="form-label">Adresse *</label>
                <textarea wire:model="adresse" class="form-control" rows="2" placeholder="Adresse complète"></textarea>
                @error('adresse')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Dates importantes -->
            <div class="col-md-3">
                <label class="form-label">Date de naissance *</label>
                <input wire:model="date_naissance" type="date" class="form-control">
                @error('date_naissance')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Date d'embauche *</label>
                <input wire:model="date_embauche" type="date" class="form-control">
                @error('date_embauche')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Permis de conduire -->
            <div class="col-md-3">
                <label class="form-label">Numéro de permis *</label>
                <input wire:model="numero_permis" type="text" class="form-control" placeholder="Numéro de permis">
                @error('numero_permis')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Permis expire le *</label>
                <input wire:model="permis_expire_le" type="date" class="form-control">
                @error('permis_expire_le')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Statut -->
            <div class="col-md-12">
                <label class="form-label">Statut *</label>
                <select wire:model="statut" class="form-control">
                    <option value="">-- Sélectionner un statut --</option>
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                    <option value="suspendu">Suspendu</option>
                </select>
                @error('statut')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="mt-3">
            <button class="btn btn-primary" type="submit">
                {{ $isEdit ? 'Modifier' : 'Ajouter' }} Chauffeur
            </button>
            @if ($isEdit)
                <button class="btn btn-secondary" wire:click="resetForm">Annuler</button>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Permis</th>
                    <th>Expire le</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($chauffeurs as $chauffeur)
                    <tr>
                        <td>{{ $chauffeur->nom ?? '' }} </td>
                        <td>{{ $chauffeur->prenom ?? '' }}</td>
                        <td>{{ $chauffeur->email }}</td>
                        <td>{{ $chauffeur->tel ?? '-' }}</td>
                        <td>{{ $chauffeur->numero_permis ?? '-' }}</td>
                        <td>
                            @if ($chauffeur->permis_expire_le)
                                {{ \Carbon\Carbon::parse($chauffeur->permis_expire_le)->format('d/m/Y') }}
                                @if (\Carbon\Carbon::parse($chauffeur->permis_expire_le)->isPast())
                                    <span class="badge bg-danger">Expiré</span>
                                @elseif(\Carbon\Carbon::parse($chauffeur->permis_expire_le)->diffInDays() <= 30)
                                    <span class="badge bg-warning">Expire bientôt</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span
                                class="badge bg-{{ $chauffeur->statut === 'actif' ? 'success' : ($chauffeur->statut === 'inactif' ? 'secondary' : 'danger') }}">
                                {{ ucfirst($chauffeur->statut) }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary"
                                wire:click="edit({{ $chauffeur->user_id }})">Modifier</button>
                            <button class="btn btn-sm btn-outline-danger"
                                wire:click="delete({{ $chauffeur->user_id }})"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce chauffeur ?')">Supprimer</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Aucun chauffeur enregistré</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
