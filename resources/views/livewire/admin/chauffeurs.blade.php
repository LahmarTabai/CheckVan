<div>
    <div>
        <div class="d-flex align-items-center mb-4">
            <div class="me-3">
                <div class="glass-effect rounded-circle p-3">
                    <i class="fas fa-user-tie text-gradient fs-4"></i>
                </div>
            </div>
            <div>
                <h1 class="text-gradient mb-0">Gestion des Chauffeurs</h1>
                <p class="text-muted mb-0">Équipe de conduite 2050</p>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="alert alert-success-2050 alert-dismissible fade show animate-fade-in-up" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Formulaire Futuriste -->
        <div class="card-2050 mb-4 hover-lift">
            <div class="card-header-2050">
                <h6 class="mb-0">
                    <i class="fas fa-plus me-2"></i>{{ $isEdit ? 'Modifier le chauffeur' : 'Nouveau chauffeur' }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form wire:submit.prevent="save">
                    <div class="row g-4">
                        <!-- Informations de base -->
                        <div class="col-md-3">
                            <label class="form-label-2050">Nom *</label>
                            <input wire:model="nom" type="text" class="form-control-2050" placeholder="Nom">
                            @error('nom')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-2050">Prénom *</label>
                            <input wire:model="prenom" type="text" class="form-control-2050" placeholder="Prénom">
                            @error('prenom')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-2050">Email *</label>
                            <input wire:model="email" type="email" class="form-control-2050"
                                placeholder="email@example.com">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-2050">Téléphone *</label>
                            <input wire:model="tel" type="tel" class="form-control-2050"
                                placeholder="+33 6 12 34 56 78">
                            @error('tel')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Informations personnelles -->
                        <div class="col-md-6">
                            <label class="form-label-2050">Adresse</label>
                            <textarea wire:model="adresse" class="form-control-2050" rows="2" placeholder="Adresse complète"></textarea>
                            @error('adresse')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-2050">Date de naissance</label>
                            <input wire:model="date_naissance" type="date" class="form-control-2050">
                            @error('date_naissance')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-2050">Date d'embauche</label>
                            <input wire:model="date_embauche" type="date" class="form-control-2050">
                            @error('date_embauche')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Informations de permis -->
                        <div class="col-md-4">
                            <label class="form-label-2050">Numéro de permis</label>
                            <input wire:model="numero_permis" type="text" class="form-control-2050"
                                placeholder="1234567890">
                            @error('numero_permis')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-2050">Expiration du permis</label>
                            <input wire:model="permis_expire_le" type="date" class="form-control-2050">
                            @error('permis_expire_le')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-2050">Statut *</label>
                            <select wire:model="statut" class="form-control-2050">
                                <option value="">-- Sélectionner le statut --</option>
                                <option value="actif">Actif</option>
                                <option value="inactif">Inactif</option>
                                <option value="suspendu">Suspendu</option>
                            </select>
                            @error('statut')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Mot de passe -->
                        <div class="col-md-6">
                            <label class="form-label-2050">Mot de passe
                                {{ $isEdit ? '(laisser vide pour ne pas changer)' : '*' }}</label>
                            <input wire:model="password" type="password" class="form-control-2050"
                                placeholder="Mot de passe">
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-2050">Confirmation du mot de passe
                                {{ $isEdit ? '(laisser vide pour ne pas changer)' : '*' }}</label>
                            <input wire:model="password_confirmation" type="password" class="form-control-2050"
                                placeholder="Confirmer le mot de passe">
                            @error('password_confirmation')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Boutons d'action -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary-2050 me-3">
                                <i class="fas fa-save me-2"></i>{{ $isEdit ? 'Modifier' : 'Ajouter' }} Chauffeur
                            </button>
                            @if ($isEdit)
                                <button type="button" wire:click="resetForm" class="btn btn-outline-2050">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des chauffeurs Futuriste -->
        <div class="card-2050 hover-lift">
            <div class="card-header-2050">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Liste des chauffeurs
                    <span class="badge badge-success-2050 ms-2">{{ $chauffeurs->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-2050 mb-0">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user me-2"></i>Chauffeur</th>
                                <th><i class="fas fa-envelope me-2"></i>Contact</th>
                                <th><i class="fas fa-id-card me-2"></i>Permis</th>
                                <th><i class="fas fa-info-circle me-2"></i>Statut</th>
                                <th><i class="fas fa-cogs me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($chauffeurs as $chauffeur)
                                <tr class="animate-fade-in-up">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="glass-effect rounded-circle p-2 me-3">
                                                <i class="fas fa-user text-gradient"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $chauffeur->nom }} {{ $chauffeur->prenom }}</strong>
                                                @if ($chauffeur->date_embauche)
                                                    <br><small class="text-muted">Embauché le
                                                        {{ \Carbon\Carbon::parse($chauffeur->date_embauche)->format('d/m/Y') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <i class="fas fa-envelope me-2 text-muted"></i>{{ $chauffeur->email }}
                                            @if ($chauffeur->tel)
                                                <br><i class="fas fa-phone me-2 text-muted"></i>{{ $chauffeur->tel }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if ($chauffeur->numero_permis)
                                            <div>
                                                <strong>{{ $chauffeur->numero_permis }}</strong>
                                                @if ($chauffeur->permis_expire_le)
                                                    <br><small class="text-muted">Expire le
                                                        {{ \Carbon\Carbon::parse($chauffeur->permis_expire_le)->format('d/m/Y') }}</small>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">Non renseigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($chauffeur->statut === 'actif')
                                            <span class="badge badge-success-2050">
                                                <i class="fas fa-check me-1"></i>Actif
                                            </span>
                                        @elseif($chauffeur->statut === 'inactif')
                                            <span class="badge badge-warning-2050">
                                                <i class="fas fa-pause me-1"></i>Inactif
                                            </span>
                                        @else
                                            <span class="badge badge-danger-2050">
                                                <i class="fas fa-ban me-1"></i>Suspendu
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group-actions">
                                            <button wire:click="edit({{ $chauffeur->user_id }})"
                                                class="btn btn-warning-2050 btn-sm" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="destroy({{ $chauffeur->user_id }})"
                                                class="btn btn-danger-2050 btn-sm"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce chauffeur ?')"
                                                title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <div class="glass-effect rounded-circle p-4 mx-auto mb-3"
                                            style="width: 80px; height: 80px;">
                                            <i class="fas fa-user-tie text-gradient fs-2"></i>
                                        </div>
                                        <h5>Aucun chauffeur trouvé</h5>
                                        <p class="mb-0">Ajoutez votre premier chauffeur pour commencer</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
