<div>
    <div>
        <div class="d-flex align-items-center mb-4">
            <div class="me-3">
                <div class="glass-effect rounded-circle p-3">
                    <i class="fas fa-tasks text-gradient fs-4"></i>
                </div>
            </div>
            <div>
                <h1 class="text-gradient mb-0">Gestion des Tâches</h1>
                <p class="text-muted mb-0">Planification et suivi des missions 2050</p>
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
                    <i
                        class="fas fa-{{ $isEdit ? 'edit' : 'plus' }} me-2"></i>{{ $isEdit ? 'Modifier la tâche' : 'Nouvelle tâche' }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'create' }}">
                    <div class="form-section-2050">
                        <h6 class="section-title-2050">
                            <i class="fas fa-info-circle me-2"></i>Informations de base
                        </h6>

                        <div class="form-row-2050">
                            <div class="form-col-2050 col-md-4">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">
                                        Chauffeur <span class="required">*</span>
                                    </label>
                                    <select wire:model="chauffeur_id" class="form-control-2050 select2-2050">
                                        <option value="">Sélectionner un chauffeur</option>
                                        @foreach ($chauffeurs as $chauffeur)
                                            <option value="{{ $chauffeur->user_id }}">
                                                {{ $chauffeur->nom }} {{ $chauffeur->prenom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('chauffeur_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-col-2050 col-md-4">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">
                                        Véhicule <span class="required">*</span>
                                    </label>
                                    <select wire:model="vehicule_id" class="form-control-2050 select2-2050">
                                        <option value="">Sélectionner un véhicule</option>
                                        @foreach ($vehicules as $vehicule)
                                            <option value="{{ $vehicule->id }}">
                                                {{ $vehicule->immatriculation }} -
                                                {{ $vehicule->marque->nom ?? 'N/A' }}
                                                {{ $vehicule->modele->nom ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('vehicule_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-col-2050 col-md-4">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">
                                        Date de début <span class="required">*</span>
                                    </label>
                                    <input type="datetime-local" wire:model="start_date" class="form-control-2050">
                                    @error('start_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-2050">
                        <h6 class="section-title-2050">
                            <i class="fas fa-tasks me-2"></i>Détails de la tâche
                        </h6>

                        <div class="form-row-2050">
                            <div class="form-col-2050 col-md-4">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">
                                        Type de tâche <span class="required">*</span>
                                    </label>
                                    <select wire:model="type_tache" class="form-control-2050 select2-2050">
                                        <option value="autre">Autre</option>
                                        <option value="maintenance">Maintenance</option>
                                        <option value="livraison">Livraison</option>
                                        <option value="inspection">Inspection</option>
                                    </select>
                                    @error('type_tache')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-col-2050 col-md-8">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">Description</label>
                                    <textarea wire:model="description" class="form-control-2050" rows="3"
                                        placeholder="Décrivez les détails de la tâche..."></textarea>
                                    <small class="form-help-2050">Décrivez les instructions spécifiques pour cette
                                        tâche</small>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-2050">
                        <h6 class="section-title-2050">
                            <i class="fas fa-tachometer-alt me-2"></i>Données véhicule
                        </h6>

                        <div class="form-row-2050">
                            <div class="form-col-2050 col-md-3">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">Kilométrage de début</label>
                                    <input type="number" wire:model="debut_kilometrage" class="form-control-2050"
                                        placeholder="Ex: 50000" min="0">
                                    <small class="form-help-2050">Kilométrage au début de la tâche</small>
                                    @error('debut_kilometrage')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-col-2050 col-md-3">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">Carburant de début (%)</label>
                                    <input type="number" wire:model="debut_carburant" class="form-control-2050"
                                        placeholder="Ex: 75" min="0" max="100" step="0.1">
                                    <small class="form-help-2050">Niveau de carburant au début</small>
                                    @error('debut_carburant')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-col-2050 col-md-3">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">Kilométrage de fin</label>
                                    <input type="number" wire:model="fin_kilometrage" class="form-control-2050"
                                        placeholder="Ex: 50150" min="0">
                                    <small class="form-help-2050">Kilométrage à la fin de la tâche</small>
                                    @error('fin_kilometrage')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-col-2050 col-md-3">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">Carburant de fin (%)</label>
                                    <input type="number" wire:model="fin_carburant" class="form-control-2050"
                                        placeholder="Ex: 70" min="0" max="100" step="0.1">
                                    <small class="form-help-2050">Niveau de carburant à la fin</small>
                                    @error('fin_carburant')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions-2050">
                        <button type="submit" class="btn btn-primary-2050">
                            <i class="fas fa-{{ $isEdit ? 'save' : 'plus' }} me-2"></i>
                            {{ $isEdit ? 'Mettre à jour' : 'Créer' }} la tâche
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
                                placeholder="Chauffeur, véhicule, immatriculation...">
                        </div>
                    </div>

                    <div class="form-col-2050 col-md-2">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Statut</label>
                            <select wire:model.live="statusFilter" class="form-control-2050 select2-2050">
                                <option value="">Tous</option>
                                <option value="en_attente">En attente</option>
                                <option value="en_cours">En cours</option>
                                <option value="terminée">Terminée</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-col-2050 col-md-2">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Validation</label>
                            <select wire:model.live="validationFilter" class="form-control-2050 select2-2050">
                                <option value="">Toutes</option>
                                <option value="1">Validées</option>
                                <option value="0">Non validées</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-col-2050 col-md-2">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Chauffeur</label>
                            <select wire:model.live="chauffeurFilter" class="form-control-2050 select2-2050">
                                <option value="">Tous</option>
                                @foreach ($chauffeurs as $chauffeur)
                                    <option value="{{ $chauffeur->user_id }}">
                                        {{ $chauffeur->nom }} {{ $chauffeur->prenom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-col-2050 col-md-2">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Véhicule</label>
                            <select wire:model.live="vehiculeFilter" class="form-control-2050 select2-2050">
                                <option value="">Tous</option>
                                @foreach ($vehicules as $vehicule)
                                    <option value="{{ $vehicule->id }}">
                                        {{ $vehicule->immatriculation }}
                                    </option>
                                @endforeach
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
                <!-- Ligne 2 -->
                <div class="row g-3 mt-3">
                    <div class="col-md-6">
                        <label class="form-label-2050">Date de début - Début</label>
                        <input type="date" wire:model.live="dateDebutFilter" class="form-control-2050">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-2050">Date de début - Fin</label>
                        <input type="date" wire:model.live="dateFinFilter" class="form-control-2050">
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des tâches -->
        <div class="card-2050 hover-lift">
            <div class="card-header-2050">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-list me-2"></i>Liste des tâches
                        <span class="badge badge-success-2050 ms-2">{{ $taches->total() }}</span>
                    </h6>
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
                                    <button wire:click="sortBy('chauffeur_id')"
                                        class="btn btn-link p-0 text-decoration-none text-primary">
                                        <i class="fas fa-user me-2"></i>Chauffeur
                                        @if ($sortField === 'chauffeur_id')
                                            <i
                                                class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @else
                                            <i class="fas fa-sort ms-1 text-muted"></i>
                                        @endif
                                    </button>
                                </th>
                                <th class="text-primary"><i class="fas fa-car me-2"></i>Véhicule</th>
                                <th>
                                    <button wire:click="sortBy('type_tache')"
                                        class="btn btn-link p-0 text-decoration-none text-primary">
                                        <i class="fas fa-tasks me-2"></i>Type
                                        @if ($sortField === 'type_tache')
                                            <i
                                                class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @else
                                            <i class="fas fa-sort ms-1 text-muted"></i>
                                        @endif
                                    </button>
                                </th>
                                <th>
                                    <button wire:click="sortBy('start_date')"
                                        class="btn btn-link p-0 text-decoration-none text-primary">
                                        <i class="fas fa-calendar me-2"></i>Date de début
                                        @if ($sortField === 'start_date')
                                            <i
                                                class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @else
                                            <i class="fas fa-sort ms-1 text-muted"></i>
                                        @endif
                                    </button>
                                </th>
                                <th>
                                    <button wire:click="sortBy('status')"
                                        class="btn btn-link p-0 text-decoration-none text-primary">
                                        <i class="fas fa-info-circle me-2"></i>Statut
                                        @if ($sortField === 'status')
                                            <i
                                                class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @else
                                            <i class="fas fa-sort ms-1 text-muted"></i>
                                        @endif
                                    </button>
                                </th>
                                <th>
                                    <button wire:click="sortBy('is_validated')"
                                        class="btn btn-link p-0 text-decoration-none text-primary">
                                        <i class="fas fa-check-circle me-2"></i>Validation
                                        @if ($sortField === 'is_validated')
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
                            @forelse($taches as $tache)
                                <tr class="animate-fade-in-up">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="glass-effect rounded-circle p-2 me-3">
                                                <i class="fas fa-user text-gradient"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $tache->chauffeur->nom ?? '-' }}
                                                    {{ $tache->chauffeur->prenom ?? '' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $tache->chauffeur->tel ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="glass-effect rounded-circle p-2 me-3">
                                                <i class="fas fa-car text-gradient"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $tache->vehicule->immatriculation ?? '-' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $tache->vehicule->marque->nom ?? '-' }}
                                                    {{ $tache->vehicule->modele->nom ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $typeClasses = [
                                                'maintenance' => 'badge-warning-2050',
                                                'livraison' => 'badge-info-2050',
                                                'inspection' => 'badge-primary-2050',
                                                'autre' => 'badge-secondary-2050',
                                            ];
                                            $typeIcons = [
                                                'maintenance' => 'wrench',
                                                'livraison' => 'truck',
                                                'inspection' => 'search',
                                                'autre' => 'question',
                                            ];
                                        @endphp
                                        <span
                                            class="badge {{ $typeClasses[$tache->type_tache] ?? 'badge-secondary-2050' }}">
                                            <i
                                                class="fas fa-{{ $typeIcons[$tache->type_tache] ?? 'question' }} me-1"></i>
                                            {{ ucfirst($tache->type_tache) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar text-primary me-2"></i>
                                            <div>
                                                <strong>{{ $tache->start_date ? $tache->start_date->format('d/m/Y') : '-' }}</strong>
                                                <br>
                                                <small
                                                    class="text-muted">{{ $tache->start_date ? $tache->start_date->format('H:i') : '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'en_attente' => 'badge-warning-2050',
                                                'en_cours' => 'badge-info-2050',
                                                'terminée' => 'badge-success-2050',
                                            ];
                                            $statusIcons = [
                                                'en_attente' => 'clock',
                                                'en_cours' => 'play',
                                                'terminée' => 'check',
                                            ];
                                        @endphp
                                        <span
                                            class="badge {{ $statusClasses[$tache->status] ?? 'badge-secondary-2050' }}">
                                            <i
                                                class="fas fa-{{ $statusIcons[$tache->status] ?? 'question' }} me-1"></i>
                                            {{ ucfirst($tache->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($tache->is_validated)
                                            <span class="badge badge-success-2050">
                                                <i class="fas fa-check me-1"></i>Validée
                                            </span>
                                        @else
                                            <span class="badge badge-warning-2050">
                                                <i class="fas fa-clock me-1"></i>En attente
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button wire:click="showDetails({{ $tache->id }})"
                                                class="btn btn-info-2050 btn-sm" title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if (!$tache->is_validated)
                                                <button wire:click="valider({{ $tache->id }})"
                                                    class="btn btn-success-2050 btn-sm" title="Valider la tâche">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <button wire:click="edit({{ $tache->id }})"
                                                class="btn btn-warning-2050 btn-sm" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button"
                                                wire:click.prevent="confirmDelete({{ $tache->id }})"
                                                class="btn btn-danger-2050 btn-sm" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <h5>Aucune tâche trouvée</h5>
                                            <p>Commencez par créer une nouvelle tâche ou ajustez vos filtres.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $taches->links() }}
        </div>
    </div>

    <!-- Modal Détails Tâche -->
    @if ($showDetailsModal && $selectedTache)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);"
            wire:click.self="closeDetailsModal">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content-2050">
                    <div class="modal-header-2050">
                        <h5 class="modal-title">
                            <i class="fas fa-info-circle me-2"></i>Détails de la tâche
                        </h5>
                        <button type="button" class="btn-close-2050" wire:click="closeDetailsModal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body-2050">
                        <div class="row g-4">
                            <!-- Informations chauffeur -->
                            <div class="col-md-6">
                                <div class="card-2050">
                                    <div class="card-header-2050">
                                        <h6 class="mb-0">
                                            <i class="fas fa-user me-2"></i>Chauffeur assigné
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="glass-effect rounded-circle p-3 me-3">
                                                <i class="fas fa-user text-gradient"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $selectedTache->chauffeur->nom ?? '-' }}
                                                    {{ $selectedTache->chauffeur->prenom ?? '' }}</h6>
                                                <p class="text-muted mb-0">
                                                    {{ $selectedTache->chauffeur->email ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <small class="text-muted">Téléphone</small>
                                                <p class="mb-0">{{ $selectedTache->chauffeur->tel ?? '-' }}</p>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Statut</small>
                                                <p class="mb-0">
                                                    <span class="badge badge-primary-2050">
                                                        {{ ucfirst($selectedTache->chauffeur->statut ?? 'inconnu') }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations véhicule -->
                            <div class="col-md-6">
                                <div class="card-2050">
                                    <div class="card-header-2050">
                                        <h6 class="mb-0">
                                            <i class="fas fa-car me-2"></i>Véhicule assigné
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="glass-effect rounded-circle p-3 me-3">
                                                <i class="fas fa-car text-gradient"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">
                                                    {{ $selectedTache->vehicule->immatriculation ?? '-' }}</h6>
                                                <p class="text-muted mb-0">
                                                    {{ $selectedTache->vehicule->marque->nom ?? '-' }}
                                                    {{ $selectedTache->vehicule->modele->nom ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <small class="text-muted">Année</small>
                                                <p class="mb-0">{{ $selectedTache->vehicule->annee ?? '-' }}</p>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Statut</small>
                                                <p class="mb-0">
                                                    <span class="badge badge-primary-2050">
                                                        {{ ucfirst($selectedTache->vehicule->statut ?? 'inconnu') }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations tâche -->
                            <div class="col-12">
                                <div class="card-2050">
                                    <div class="card-header-2050">
                                        <h6 class="mb-0">
                                            <i class="fas fa-tasks me-2"></i>Détails de la tâche
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <small class="text-muted">Date de début</small>
                                                <p class="mb-0">
                                                    <i class="fas fa-calendar text-primary me-1"></i>
                                                    {{ $selectedTache->start_date ? $selectedTache->start_date->format('d/m/Y H:i') : '-' }}
                                                </p>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Date de fin</small>
                                                <p class="mb-0">
                                                    <i class="fas fa-calendar text-primary me-1"></i>
                                                    {{ $selectedTache->end_date ? $selectedTache->end_date->format('d/m/Y H:i') : 'Non terminée' }}
                                                </p>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Statut</small>
                                                <p class="mb-0">
                                                    @php
                                                        $statusClasses = [
                                                            'en_attente' => 'badge-warning-2050',
                                                            'en_cours' => 'badge-info-2050',
                                                            'terminée' => 'badge-success-2050',
                                                        ];
                                                    @endphp
                                                    <span
                                                        class="badge {{ $statusClasses[$selectedTache->status] ?? 'badge-secondary-2050' }}">
                                                        {{ ucfirst($selectedTache->status) }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Validation</small>
                                                <p class="mb-0">
                                                    @if ($selectedTache->is_validated)
                                                        <span class="badge badge-success-2050">
                                                            <i class="fas fa-check me-1"></i>Validée
                                                        </span>
                                                    @else
                                                        <span class="badge badge-warning-2050">
                                                            <i class="fas fa-clock me-1"></i>En attente
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        @if ($selectedTache->start_latitude && $selectedTache->start_longitude)
                                            <div class="row g-3 mt-3">
                                                <div class="col-md-6">
                                                    <small class="text-muted">Position de début</small>
                                                    <p class="mb-0">
                                                        <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                                        {{ $selectedTache->start_latitude }},
                                                        {{ $selectedTache->start_longitude }}
                                                    </p>
                                                </div>
                                                @if ($selectedTache->end_latitude && $selectedTache->end_longitude)
                                                    <div class="col-md-6">
                                                        <small class="text-muted">Position de fin</small>
                                                        <p class="mb-0">
                                                            <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                                            {{ $selectedTache->end_latitude }},
                                                            {{ $selectedTache->end_longitude }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer-2050">
                        <button type="button" class="btn btn-secondary-2050" wire:click="closeDetailsModal">
                            <i class="fas fa-times me-2"></i>Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Confirmation Suppression -->
    @if ($showDeleteModal && $tacheToDelete)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5); z-index: 9999;"
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
                            <i class="fas fa-tasks fa-3x text-danger mb-3"></i>
                            <h5>Êtes-vous sûr de vouloir supprimer cette tâche ?</h5>
                            <p class="text-muted">Cette action est irréversible.</p>
                        </div>
                    </div>
                    <div class="modal-footer card-header-2050">
                        <button type="button" class="btn btn-outline-2050" wire:click="cancelDelete">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="button" class="btn btn-danger-2050"
                            wire:click="delete({{ $tacheToDelete }})">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
   
</div>
</div>
