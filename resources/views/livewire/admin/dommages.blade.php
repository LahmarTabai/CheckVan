<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-car-crash me-2"></i>
                        Gestion des Dommages
                    </h4>
                </div>

                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $stats['total'] }}</h4>
                                            <p class="mb-0">Total Dommages</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-car-crash fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $stats['non_repares'] }}</h4>
                                            <p class="mb-0">En Attente</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $stats['majeurs'] }}</h4>
                                            <p class="mb-0">Dommages Majeurs</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Recherche</label>
                                        <input type="text" class="form-control" wire:model.live="search"
                                            placeholder="Véhicule, chauffeur, description...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Type</label>
                                        <select class="form-select" wire:model.live="filterType">
                                            <option value="">Tous les types</option>
                                            <option value="rayure">Rayure</option>
                                            <option value="bosse">Bosse</option>
                                            <option value="choc">Choc</option>
                                            <option value="autre">Autre</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Sévérité</label>
                                        <select class="form-select" wire:model.live="filterSeverite">
                                            <option value="">Toutes les sévérités</option>
                                            <option value="mineur">Mineur</option>
                                            <option value="moyen">Moyen</option>
                                            <option value="majeur">Majeur</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Statut</label>
                                        <select class="form-select" wire:model.live="filterStatus">
                                            <option value="">Tous les statuts</option>
                                            <option value="en_attente">En attente</option>
                                            <option value="reparé">Réparé</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des dommages -->
                    <div class="card">
                        <div class="card-body">
                            @if ($dommages->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Véhicule</th>
                                                <th>Chauffeur</th>
                                                <th>Type</th>
                                                <th>Sévérité</th>
                                                <th>Description</th>
                                                <th>Position</th>
                                                <th>Photo</th>
                                                <th>Statut</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dommages as $dommage)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $dommage->affectation->vehicule->marque }}
                                                            {{ $dommage->affectation->vehicule->modele }}</strong><br>
                                                        <small
                                                            class="text-muted">{{ $dommage->affectation->vehicule->immatriculation }}</small>
                                                    </td>
                                                    <td>
                                                        <strong>{{ $dommage->chauffeur->name }}</strong><br>
                                                        <small
                                                            class="text-muted">{{ $dommage->chauffeur->email }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">
                                                            {{ ucfirst($dommage->type) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge
                                                            @if ($dommage->severite == 'majeur') bg-danger
                                                            @elseif($dommage->severite == 'moyen') bg-warning
                                                            @else bg-info @endif">
                                                            {{ ucfirst($dommage->severite) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small>{{ Str::limit($dommage->description, 50) }}</small>
                                                    </td>
                                                    <td>
                                                        @if ($dommage->coord_x && $dommage->coord_y)
                                                            <small>X: {{ $dommage->coord_x }}%<br>Y:
                                                                {{ $dommage->coord_y }}%</small>
                                                        @else
                                                            <small class="text-muted">Non défini</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($dommage->photo_path)
                                                            <img src="{{ Storage::url($dommage->photo_path) }}"
                                                                class="img-thumbnail"
                                                                style="width: 50px; height: 50px;">
                                                        @else
                                                            <small class="text-muted">Aucune</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($dommage->reparé)
                                                            <span class="badge bg-success">Réparé</span>
                                                        @else
                                                            <span class="badge bg-warning">En attente</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small>{{ $dommage->created_at->format('d/m/Y H:i') }}</small>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button class="btn btn-outline-info"
                                                                wire:click="viewDommage({{ $dommage->id }})"
                                                                title="Voir détails">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            @if (!$dommage->reparé)
                                                                <button class="btn btn-outline-success"
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
                                <div class="d-flex justify-content-center">
                                    {{ $dommages->links() }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-car-crash fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun dommage trouvé.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de détails -->
    @if ($showModal && $selectedDommage)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails du Dommage</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Informations Véhicule</h6>
                                <p><strong>Véhicule :</strong> {{ $selectedDommage->affectation->vehicule->marque }}
                                    {{ $selectedDommage->affectation->vehicule->modele }}</p>
                                <p><strong>Immatriculation :</strong>
                                    {{ $selectedDommage->affectation->vehicule->immatriculation }}</p>

                                <h6 class="mt-3">Informations Chauffeur</h6>
                                <p><strong>Nom :</strong> {{ $selectedDommage->chauffeur->name }}</p>
                                <p><strong>Email :</strong> {{ $selectedDommage->chauffeur->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Détails du Dommage</h6>
                                <p><strong>Type :</strong>
                                    <span class="badge bg-primary">{{ ucfirst($selectedDommage->type) }}</span>
                                </p>
                                <p><strong>Sévérité :</strong>
                                    <span
                                        class="badge
                                        @if ($selectedDommage->severite == 'majeur') bg-danger
                                        @elseif($selectedDommage->severite == 'moyen') bg-warning
                                        @else bg-info @endif">
                                        {{ ucfirst($selectedDommage->severite) }}
                                    </span>
                                </p>
                                <p><strong>Statut :</strong>
                                    @if ($selectedDommage->reparé)
                                        <span class="badge bg-success">Réparé</span>
                                    @else
                                        <span class="badge bg-warning">En attente</span>
                                    @endif
                                </p>
                                <p><strong>Date :</strong> {{ $selectedDommage->created_at->format('d/m/Y H:i') }}</p>

                                @if ($selectedDommage->coord_x && $selectedDommage->coord_y)
                                    <p><strong>Position :</strong> X: {{ $selectedDommage->coord_x }}%, Y:
                                        {{ $selectedDommage->coord_y }}%</p>
                                @endif
                            </div>
                        </div>

                        @if ($selectedDommage->description)
                            <div class="mt-3">
                                <h6>Description</h6>
                                <p>{{ $selectedDommage->description }}</p>
                            </div>
                        @endif

                        @if ($selectedDommage->photo_path)
                            <div class="mt-3">
                                <h6>Photo du Dommage</h6>
                                <img src="{{ Storage::url($selectedDommage->photo_path) }}" class="img-fluid rounded"
                                    style="max-width: 100%;">
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        @if (!$selectedDommage->reparé)
                            <button type="button" class="btn btn-success"
                                wire:click="markAsRepared({{ $selectedDommage->id }})">
                                <i class="fas fa-check me-1"></i>
                                Marquer comme réparé
                            </button>
                        @endif
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
