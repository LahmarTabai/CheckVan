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
                    <div class="form-section-2050">
                        <h6 class="section-title-2050">
                            <i class="fas fa-user me-2"></i>Informations personnelles
                        </h6>

                        <div class="form-row-2050">
                            <div class="form-col-2050 col-md-3">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">
                                        Nom <span class="required">*</span>
                                    </label>
                                    <input wire:model="nom" type="text" class="form-control-2050" placeholder="Nom">
                                    @error('nom')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-col-2050 col-md-3">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">
                                        Prénom <span class="required">*</span>
                                    </label>
                                    <input wire:model="prenom" type="text" class="form-control-2050"
                                        placeholder="Prénom">
                                    @error('prenom')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-col-2050 col-md-3">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">
                                        Email <span class="required">*</span>
                                    </label>
                                    <input wire:model="email" type="email" class="form-control-2050"
                                        placeholder="email@example.com">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-col-2050 col-md-3">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">
                                        Téléphone <span class="required">*</span>
                                    </label>
                                    <input wire:model="tel" type="tel" class="form-control-2050"
                                        placeholder="+33 6 12 34 56 78">
                                    @error('tel')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-row-2050">
                            <div class="form-col-2050 col-md-6">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">Adresse</label>
                                    <textarea wire:model="adresse" class="form-control-2050" rows="2" placeholder="Adresse complète"></textarea>
                                    <small class="form-help-2050">Adresse de résidence du chauffeur</small>
                                    @error('adresse')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-col-2050 col-md-3">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">Date de naissance</label>
                                    <input wire:model="date_naissance" type="date" class="form-control-2050">
                                    <small class="form-help-2050">Date de naissance du chauffeur</small>
                                    @error('date_naissance')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-col-2050 col-md-3">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">Date d'embauche</label>
                                    <input wire:model="date_embauche" type="date" class="form-control-2050">
                                    <small class="form-help-2050">Date d'embauche du chauffeur</small>
                                    @error('date_embauche')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-2050">
                        <h6 class="section-title-2050">
                            <i class="fas fa-id-card me-2"></i>Informations de permis
                        </h6>

                        <div class="form-row-2050">
                            <div class="form-col-2050 col-md-4">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">Numéro de permis</label>
                                    <input wire:model="numero_permis" type="text" class="form-control-2050"
                                        placeholder="1234567890">
                                    <small class="form-help-2050">Numéro du permis de conduire</small>
                                    @error('numero_permis')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-col-2050 col-md-4">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">Expiration du permis</label>
                                    <input wire:model="permis_expire_le" type="date" class="form-control-2050">
                                    <small class="form-help-2050">Date d'expiration du permis</small>
                                    @error('permis_expire_le')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-col-2050 col-md-4">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">
                                        Statut <span class="required">*</span>
                                    </label>
                                    <select wire:model="statut" class="form-control-2050 select2-2050">
                                        <option value="">-- Sélectionner le statut --</option>
                                        <option value="actif">Actif</option>
                                        <option value="inactif">Inactif</option>
                                        <option value="suspendu">Suspendu</option>
                                    </select>
                                    @error('statut')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-2050">
                        <h6 class="section-title-2050">
                            <i class="fas fa-lock me-2"></i>Sécurité
                        </h6>

                        <div class="form-row-2050">
                            <div class="form-col-2050 col-md-6">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">
                                        Mot de passe {{ $isEdit ? '' : '<span class="required">*</span>' }}
                                    </label>
                                    <input wire:model="password" type="password" class="form-control-2050"
                                        placeholder="Mot de passe">
                                    <small class="form-help-2050">
                                        {{ $isEdit ? 'Laisser vide pour ne pas changer' : 'Mot de passe de connexion' }}
                                    </small>
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-col-2050 col-md-6">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">
                                        Confirmation du mot de passe
                                        {{ $isEdit ? '' : '<span class="required">*</span>' }}
                                    </label>
                                    <input wire:model="password_confirmation" type="password"
                                        class="form-control-2050" placeholder="Confirmer le mot de passe">
                                    <small class="form-help-2050">
                                        {{ $isEdit ? 'Laisser vide pour ne pas changer' : 'Confirmer le mot de passe' }}
                                    </small>
                                    @error('password_confirmation')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-2050">
                        <h6 class="section-title-2050">
                            <i class="fas fa-camera me-2"></i>Photo de profil
                        </h6>

                        <div class="form-row-2050">
                            <div class="form-col-2050 col-12">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">Photo de profil</label>
                                    <input type="file" wire:model="profile_picture" class="form-control-2050"
                                        accept="image/*">
                                    <small class="form-help-2050">Formats acceptés : JPG, PNG, GIF. Taille max :
                                        8MB.</small>
                                    @error('profile_picture')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                    @if ($profile_picture)
                                        <div class="mt-2">
                                            <img src="{{ $profile_picture->temporaryUrl() }}" alt="Aperçu"
                                                class="rounded-circle"
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                            <small class="text-success ms-2">
                                                <i class="fas fa-check me-1"></i>Photo sélectionnée
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions-2050">
                        <button type="submit" class="btn btn-primary-2050">
                            <i class="fas fa-save me-2"></i>{{ $isEdit ? 'Modifier' : 'Ajouter' }} Chauffeur
                        </button>
                        @if ($isEdit)
                            <button type="button" wire:click="resetForm" class="btn btn-outline-2050">
                                <i class="fas fa-times me-2"></i>Annuler
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Filtres Futuristes -->
        <div class="card-2050 mb-4 hover-lift">
            <div class="card-header-2050">
                <h6 class="mb-0">
                    <i class="fas fa-filter me-2"></i>Filtres Intelligents
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="form-row-2050">
                    <div class="form-col-2050 col-md-3">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Recherche générale</label>
                            <input type="text" wire:model.live="search" class="form-control-2050"
                                placeholder="Nom, prénom, email, téléphone...">
                        </div>
                    </div>

                    <div class="form-col-2050 col-md-2">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Statut</label>
                            <select wire:model.live="filterStatut" class="form-control-2050 select2-2050">
                                <option value="">Tous</option>
                                <option value="actif">Actif</option>
                                <option value="inactif">Inactif</option>
                                <option value="suspendu">Suspendu</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-col-2050 col-md-2">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Rôle</label>
                            <select wire:model.live="filterRole" class="form-control-2050 select2-2050">
                                <option value="">Tous</option>
                                <option value="admin">Admin</option>
                                <option value="chauffeur">Chauffeur</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-col-2050 col-md-2">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Permis</label>
                            <select wire:model.live="filterPermis" class="form-control-2050 select2-2050">
                                <option value="">Tous</option>
                                <option value="expire_bientot">Expire bientôt</option>
                                <option value="expire_dans_3_mois">Expire dans 3 mois</option>
                                <option value="expire_dans_6_mois">Expire dans 6 mois</option>
                                <option value="expire">Expiré</option>
                                <option value="sans_permis">Sans permis</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-col-2050 col-md-2">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Email vérifié</label>
                            <select wire:model.live="filterEmailVerified" class="form-control-2050 select2-2050">
                                <option value="">Tous</option>
                                <option value="verified">Vérifié</option>
                                <option value="not_verified">Non vérifié</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-col-2050 col-md-1">
                        <div class="form-group-2050">
                            <label class="form-label-2050">&nbsp;</label>
                            <button wire:click="resetFilters" class="btn btn-outline-2050 w-100">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ligne 2 - Dates -->
            <div class="row g-3 mt-2">
                <div class="col-md-3">
                    <label class="form-label-2050">Date d'embauche - Début</label>
                    <input type="date" wire:model.live="filterDateEmbaucheDebut" class="form-control-2050">
                </div>
                <div class="col-md-3">
                    <label class="form-label-2050">Date d'embauche - Fin</label>
                    <input type="date" wire:model.live="filterDateEmbaucheFin" class="form-control-2050">
                </div>
                <div class="col-md-3">
                    <label class="form-label-2050">Date de naissance - Début</label>
                    <input type="date" wire:model.live="filterDateNaissanceDebut" class="form-control-2050">
                </div>
                <div class="col-md-3">
                    <label class="form-label-2050">Date de naissance - Fin</label>
                    <input type="date" wire:model.live="filterDateNaissanceFin" class="form-control-2050">
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des chauffeurs Futuriste -->
    <div class="card-2050 hover-lift">
        <div class="card-header-2050">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Liste des chauffeurs
                    <span class="badge badge-success-2050 ms-2">{{ $chauffeurs->total() }}</span>
                </h5>
                <button wire:click="exportExcel" class="btn btn-success-2050 btn-sm">
                    <i class="fas fa-file-excel me-2"></i>Exporter Excel
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-2050 mb-0">
                    <thead>
                        <tr>
                            <th>
                                <button wire:click="sortBy('nom')"
                                    class="btn btn-link p-0 text-decoration-none text-primary">
                                    <i class="fas fa-user me-2"></i>Chauffeur
                                    @if ($sortField === 'nom')
                                        <i
                                            class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @else
                                        <i class="fas fa-sort ms-1 text-muted"></i>
                                    @endif
                                </button>
                            </th>
                            <th class="text-primary"><i class="fas fa-envelope me-2"></i>Contact</th>
                            <th class="text-primary"><i class="fas fa-id-card me-2"></i>Permis</th>
                            <th>
                                <button wire:click="sortBy('statut')"
                                    class="btn btn-link p-0 text-decoration-none text-primary">
                                    <i class="fas fa-info-circle me-2"></i>Statut
                                    @if ($sortField === 'statut')
                                        <i
                                            class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @else
                                        <i class="fas fa-sort ms-1 text-muted"></i>
                                    @endif
                                </button>
                            </th>
                            <th class="text-primary"><i class="fas fa-cogs me-2"></i>Actions</th>
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
                                        <button type="button"
                                            wire:click.prevent="showDetails({{ $chauffeur->user_id }})"
                                            class="btn btn-info-2050 btn-sm" title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" wire:click.prevent="edit({{ $chauffeur->user_id }})"
                                            class="btn btn-warning-2050 btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button"
                                            wire:click.prevent="confirmDelete({{ $chauffeur->user_id }})"
                                            class="btn btn-danger-2050 btn-sm" title="Supprimer">
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

    <div class="mt-4">
        {{ $chauffeurs->links() }}
    </div>


    <!-- Modal Détails Chauffeur -->
    @if ($showDetailsModal && $selectedChauffeur)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5); z-index: 9999;"
            wire:click.self="closeDetailsModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="background: white; border-radius: 8px;">
                    <div class="modal-header" style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                        <h5 class="modal-title">
                            <i class="fas fa-user-tie me-2"></i>Détails du chauffeur
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeDetailsModal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <!-- Photo de profil -->
                            <div class="col-12 text-center mb-4">
                                <div class="glass-effect rounded-circle p-3 d-inline-block">
                                    @if ($selectedChauffeur->profile_picture)
                                        <img src="{{ asset('storage/' . $selectedChauffeur->profile_picture) }}"
                                            alt="Photo de profil" class="rounded-circle"
                                            style="width: 120px; height: 120px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-gradient d-flex align-items-center justify-content-center"
                                            style="width: 120px; height: 120px;">
                                            <i class="fas fa-user text-white fs-1"></i>
                                        </div>
                                    @endif
                                </div>
                                <h4 class="text-gradient mt-3 mb-0">
                                    {{ $selectedChauffeur->nom }} {{ $selectedChauffeur->prenom }}
                                </h4>
                                <p class="text-muted mb-0">
                                    <span class="badge badge-primary-2050">
                                        {{ ucfirst($selectedChauffeur->role) }}
                                    </span>
                                </p>
                            </div>

                            <!-- Informations personnelles -->
                            <div class="col-md-6">
                                <h6 class="text-gradient mb-3">
                                    <i class="fas fa-user me-2"></i>Informations personnelles
                                </h6>
                                <div class="glass-effect p-3 rounded">
                                    <div class="mb-2">
                                        <strong>Nom complet :</strong><br>
                                        {{ $selectedChauffeur->nom }} {{ $selectedChauffeur->prenom }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Email :</strong><br>
                                        {{ $selectedChauffeur->email }}
                                        @if ($selectedChauffeur->email_verified_at)
                                            <span class="badge badge-success-2050 ms-2">
                                                <i class="fas fa-check me-1"></i>Vérifié
                                            </span>
                                        @else
                                            <span class="badge badge-warning-2050 ms-2">
                                                <i class="fas fa-exclamation me-1"></i>Non vérifié
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mb-2">
                                        <strong>Téléphone :</strong><br>
                                        {{ $selectedChauffeur->tel ?? 'Non renseigné' }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Date de naissance :</strong><br>
                                        {{ $selectedChauffeur->date_naissance ? \Carbon\Carbon::parse($selectedChauffeur->date_naissance)->format('d/m/Y') : 'Non renseignée' }}
                                    </div>
                                    <div class="mb-0">
                                        <strong>Adresse :</strong><br>
                                        {{ $selectedChauffeur->adresse ?? 'Non renseignée' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Informations professionnelles -->
                            <div class="col-md-6">
                                <h6 class="text-gradient mb-3">
                                    <i class="fas fa-briefcase me-2"></i>Informations professionnelles
                                </h6>
                                <div class="glass-effect p-3 rounded">
                                    <div class="mb-2">
                                        <strong>Rôle :</strong><br>
                                        <span class="badge badge-primary-2050">
                                            {{ ucfirst($selectedChauffeur->role) }}
                                        </span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Statut :</strong><br>
                                        @if ($selectedChauffeur->statut === 'actif')
                                            <span class="badge badge-success-2050">
                                                <i class="fas fa-check me-1"></i>Actif
                                            </span>
                                        @elseif($selectedChauffeur->statut === 'inactif')
                                            <span class="badge badge-warning-2050">
                                                <i class="fas fa-pause me-1"></i>Inactif
                                            </span>
                                        @else
                                            <span class="badge badge-danger-2050">
                                                <i class="fas fa-ban me-1"></i>Suspendu
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mb-2">
                                        <strong>Date d'embauche :</strong><br>
                                        {{ $selectedChauffeur->date_embauche ? \Carbon\Carbon::parse($selectedChauffeur->date_embauche)->format('d/m/Y') : 'Non renseignée' }}
                                    </div>
                                    <div class="mb-0">
                                        <strong>Membre depuis :</strong><br>
                                        {{ \Carbon\Carbon::parse($selectedChauffeur->created_at)->format('d/m/Y à H:i') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Informations du permis -->
                            <div class="col-12">
                                <h6 class="text-gradient mb-3">
                                    <i class="fas fa-id-card me-2"></i>Informations du permis
                                </h6>
                                <div class="glass-effect p-3 rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <strong>Numéro de permis :</strong><br>
                                                {{ $selectedChauffeur->numero_permis ?? 'Non renseigné' }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <strong>Expiration du permis :</strong><br>
                                                @if ($selectedChauffeur->permis_expire_le)
                                                    {{ \Carbon\Carbon::parse($selectedChauffeur->permis_expire_le)->format('d/m/Y') }}
                                                    @php
                                                        $expirationDate = \Carbon\Carbon::parse(
                                                            $selectedChauffeur->permis_expire_le,
                                                        );
                                                        $now = \Carbon\Carbon::now();
                                                        $daysUntilExpiration = $now->diffInDays($expirationDate, false);
                                                    @endphp
                                                    @if ($daysUntilExpiration < 0)
                                                        <span class="badge badge-danger-2050 ms-2">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>Expiré
                                                        </span>
                                                    @elseif($daysUntilExpiration <= 30)
                                                        <span class="badge badge-warning-2050 ms-2">
                                                            <i class="fas fa-clock me-1"></i>Expire bientôt
                                                        </span>
                                                    @else
                                                        <span class="badge badge-success-2050 ms-2">
                                                            <i class="fas fa-check me-1"></i>Valide
                                                        </span>
                                                    @endif
                                                @else
                                                    Non renseignée
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer card-header-2050">
                        <button type="button" class="btn btn-outline-2050" wire:click="closeDetailsModal">
                            <i class="fas fa-times me-2"></i>Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Confirmation Suppression -->
    @if ($showDeleteModal && $chauffeurToDelete)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);"
            wire:click.self="cancelDelete">
            <div class="modal-dialog">
                <div class="modal-content card-2050">
                    <div class="modal-header card-header-2050">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Confirmation de suppression
                        </h5>
                        <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="text-center">
                            <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                            <h5>Êtes-vous sûr de vouloir supprimer ce chauffeur ?</h5>
                            <p class="text-muted">Cette action est irréversible.</p>
                        </div>
                    </div>
                    <div class="modal-footer card-header-2050">
                        <button type="button" class="btn btn-outline-2050" wire:click="cancelDelete">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="button" class="btn btn-danger-2050"
                            wire:click="destroy({{ $chauffeurToDelete }})">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
