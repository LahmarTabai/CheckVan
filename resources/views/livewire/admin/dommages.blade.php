<div>
    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            <div class="glass-effect rounded-circle p-3">
                <i class="fas fa-exclamation-triangle text-gradient fs-4"></i>
            </div>
        </div>
        <div>
            <h1 class="text-gradient mb-0">Gestion des Dommages</h1>
            <p class="text-muted mb-0">Suivi et réparation des dommages 2050</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row g-4 mb-5">
        <div class="col-lg-4 col-md-6">
            <div class="card-2050 hover-lift">
                <div class="card-body-2050 text-center p-4">
                    <div class="stat-icon-2050 mb-3">
                        <i class="fas fa-car-crash text-primary"></i>
                    </div>
                    <h3 class="stat-number-2050 mb-1">{{ $stats['total'] ?? 0 }}</h3>
                    <p class="stat-label-2050 mb-0">Total Dommages</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card-2050 hover-lift">
                <div class="card-body-2050 text-center p-4">
                    <div class="stat-icon-2050 mb-3">
                        <i class="fas fa-clock text-warning"></i>
                    </div>
                    <h3 class="stat-number-2050 mb-1">{{ $stats['non_repares'] ?? 0 }}</h3>
                    <p class="stat-label-2050 mb-0">En Attente</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card-2050 hover-lift">
                <div class="card-body-2050 text-center p-4">
                    <div class="stat-icon-2050 mb-3">
                        <i class="fas fa-exclamation-triangle text-danger"></i>
                    </div>
                    <h3 class="stat-number-2050 mb-1">{{ $stats['majeurs'] ?? 0 }}</h3>
                    <p class="stat-label-2050 mb-0">Dommages Majeurs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card-2050 mb-4 hover-lift">
        <div class="card-header-2050">
            <h6 class="mb-0">
                <i class="fas fa-filter me-2"></i>Filtres Intelligents
            </h6>
        </div>
        <div class="card-body-2050 p-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label-2050">Recherche</label>
                    <input type="text" class="form-control-2050" wire:model.live="search"
                        placeholder="Véhicule, chauffeur, description...">
                </div>
                <div class="col-md-3">
                    <label class="form-label-2050">Type</label>
                    <select class="form-control-2050" wire:model.live="filterType">
                        <option value="">Tous les types</option>
                        <option value="rayure">Rayure</option>
                        <option value="bosse">Bosse</option>
                        <option value="choc">Choc</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-2050">Sévérité</label>
                    <select class="form-control-2050" wire:model.live="filterSeverite">
                        <option value="">Toutes les sévérités</option>
                        <option value="mineur">Mineur</option>
                        <option value="moyen">Moyen</option>
                        <option value="majeur">Majeur</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-2050">Statut</label>
                    <select class="form-control-2050" wire:model.live="filterStatus">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente">En attente</option>
                        <option value="reparé">Réparé</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des dommages -->
    <div class="card-2050 hover-lift">
        <div class="card-header-2050">
            <h6 class="mb-0">
                <i class="fas fa-list me-2"></i>Liste des Dommages
                <span class="badge badge-primary-2050 ms-2">{{ $dommages->count() ?? 0 }}</span>
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($dommages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-2050 mb-0">
                        <thead>
                            <tr>
                                <th class="text-primary"><i class="fas fa-car me-2"></i>Véhicule</th>
                                <th class="text-primary"><i class="fas fa-user me-2"></i>Chauffeur</th>
                                <th class="text-primary"><i class="fas fa-tag me-2"></i>Type</th>
                                <th class="text-primary"><i class="fas fa-exclamation-triangle me-2"></i>Sévérité</th>
                                <th class="text-primary"><i class="fas fa-align-left me-2"></i>Description</th>
                                <th class="text-primary"><i class="fas fa-map-marker-alt me-2"></i>Position</th>
                                <th class="text-primary"><i class="fas fa-camera me-2"></i>Photo</th>
                                <th class="text-primary"><i class="fas fa-info-circle me-2"></i>Statut</th>
                                <th class="text-primary"><i class="fas fa-calendar me-2"></i>Date</th>
                                <th class="text-primary"><i class="fas fa-cogs me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dommages as $dommage)
                                <tr>
                                    <td>
                                        <div>
                                            <div class="fw-medium">
                                                {{ $dommage->affectation->vehicule->marque->nom ?? 'N/A' }}
                                                {{ $dommage->affectation->vehicule->modele->nom ?? 'N/A' }}</div>
                                            <small
                                                class="text-muted">{{ $dommage->affectation->vehicule->immatriculation ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-medium">{{ $dommage->chauffeur->nom ?? 'N/A' }}
                                                {{ $dommage->chauffeur->prenom ?? '' }}</div>
                                            <small
                                                class="text-muted">{{ $dommage->chauffeur->email ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary-2050">
                                            {{ ucfirst($dommage->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $dommage->severite == 'majeur' ? 'danger' : ($dommage->severite == 'moyen' ? 'warning' : 'info') }}-2050">
                                            {{ ucfirst($dommage->severite) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($dommage->description, 50) }}</small>
                                    </td>
                                    <td>
                                        @if ($dommage->coord_x && $dommage->coord_y)
                                            <small>X: {{ $dommage->coord_x }}%<br>Y: {{ $dommage->coord_y }}%</small>
                                        @else
                                            <small class="text-muted">Non défini</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($dommage->photo_path)
                                            <img src="{{ Storage::url($dommage->photo_path) }}" class="img-thumbnail"
                                                style="width: 50px; height: 50px; border-radius: 8px;">
                                        @else
                                            <small class="text-muted">Aucune</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($dommage->reparé)
                                            <span class="badge badge-success-2050">
                                                <i class="fas fa-check me-1"></i>Réparé
                                            </span>
                                        @else
                                            <span class="badge badge-warning-2050">
                                                <i class="fas fa-clock me-1"></i>En attente
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $dommage->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-info-2050 btn-sm"
                                                wire:click="viewDommage({{ $dommage->id }})" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if (!$dommage->reparé)
                                                <button class="btn btn-success-2050 btn-sm"
                                                    wire:click="markAsRepared({{ $dommage->id }})"
                                                    title="Marquer comme réparé">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center p-3">
                    {{ $dommages->links() }}
                </div>
            @else
                <div class="empty-state-2050 text-center py-5">
                    <i class="fas fa-car-crash fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun dommage trouvé</h5>
                    <p class="text-muted">Aucun dommage ne correspond à vos critères de recherche</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de détails -->
    @if ($showModal && $selectedDommage)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content modal-2050">
                    <div class="modal-header modal-header-2050">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Détails du Dommage
                        </h5>
                        <button type="button" class="btn-close btn-close-2050" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body modal-body-2050">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-section-2050">
                                    <h6 class="section-title-2050">
                                        <i class="fas fa-car me-2"></i>Informations Véhicule
                                    </h6>
                                    <div class="info-item-2050">
                                        <strong>Véhicule :</strong>
                                        {{ $selectedDommage->affectation->vehicule->marque->nom ?? 'N/A' }}
                                        {{ $selectedDommage->affectation->vehicule->modele->nom ?? 'N/A' }}
                                    </div>
                                    <div class="info-item-2050">
                                        <strong>Immatriculation :</strong>
                                        {{ $selectedDommage->affectation->vehicule->immatriculation ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="info-section-2050 mt-4">
                                    <h6 class="section-title-2050">
                                        <i class="fas fa-user me-2"></i>Informations Chauffeur
                                    </h6>
                                    <div class="info-item-2050">
                                        <strong>Nom :</strong>
                                        {{ $selectedDommage->chauffeur->nom ?? 'N/A' }}
                                        {{ $selectedDommage->chauffeur->prenom ?? '' }}
                                    </div>
                                    <div class="info-item-2050">
                                        <strong>Email :</strong> {{ $selectedDommage->chauffeur->email ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-section-2050">
                                    <h6 class="section-title-2050">
                                        <i class="fas fa-info-circle me-2"></i>Détails du Dommage
                                    </h6>
                                    <div class="info-item-2050">
                                        <strong>Type :</strong>
                                        <span
                                            class="badge badge-primary-2050 ms-2">{{ ucfirst($selectedDommage->type) }}</span>
                                    </div>
                                    <div class="info-item-2050">
                                        <strong>Sévérité :</strong>
                                        <span
                                            class="badge badge-{{ $selectedDommage->severite == 'majeur'
                                                ? 'danger'
                                                : ($selectedDommage->severite == 'moyen'
                                                    ? 'warning'
                                                    : 'info') }}-2050 ms-2">
                                            {{ ucfirst($selectedDommage->severite) }}
                                        </span>
                                    </div>
                                    <div class="info-item-2050">
                                        <strong>Statut :</strong>
                                        @if ($selectedDommage->reparé)
                                            <span class="badge badge-success-2050 ms-2">
                                                <i class="fas fa-check me-1"></i>Réparé
                                            </span>
                                        @else
                                            <span class="badge badge-warning-2050 ms-2">
                                                <i class="fas fa-clock me-1"></i>En attente
                                            </span>
                                        @endif
                                    </div>
                                    <div class="info-item-2050">
                                        <strong>Date :</strong> {{ $selectedDommage->created_at->format('d/m/Y H:i') }}
                                    </div>

                                    @if ($selectedDommage->coord_x && $selectedDommage->coord_y)
                                        <div class="info-item-2050">
                                            <strong>Position :</strong> X: {{ $selectedDommage->coord_x }}%, Y:
                                            {{ $selectedDommage->coord_y }}%
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($selectedDommage->description)
                            <div class="info-section-2050 mt-4">
                                <h6 class="section-title-2050">
                                    <i class="fas fa-align-left me-2"></i>Description
                                </h6>
                                <div class="description-2050">
                                    {{ $selectedDommage->description }}
                                </div>
                            </div>
                        @endif

                        @if ($selectedDommage->photo_path)
                            <div class="info-section-2050 mt-4">
                                <h6 class="section-title-2050">
                                    <i class="fas fa-camera me-2"></i>Photo du Dommage
                                </h6>
                                <div class="photo-container-2050">
                                    <img src="{{ Storage::url($selectedDommage->photo_path) }}"
                                        class="img-fluid rounded photo-2050"
                                        style="max-width: 100%; border-radius: var(--border-radius);">
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer modal-footer-2050">
                        @if (!$selectedDommage->reparé)
                            <button type="button" class="btn btn-success-2050"
                                wire:click="markAsRepared({{ $selectedDommage->id }})">
                                <i class="fas fa-check me-1"></i>
                                Marquer comme réparé
                            </button>
                        @endif
                        <button type="button" class="btn btn-secondary-2050" wire:click="closeModal">
                            <i class="fas fa-times me-1"></i>
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
