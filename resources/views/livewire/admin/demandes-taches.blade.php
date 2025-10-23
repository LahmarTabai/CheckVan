<div>
    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            <div class="glass-effect rounded-circle p-3">
                <i class="fas fa-clock text-gradient fs-4"></i>
            </div>
        </div>
        <div>
            <h1 class="text-gradient mb-0">Demandes de Tâches</h1>
            <p class="text-muted mb-0">Validation des demandes de missions en attente</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success-2050 alert-dismissible fade show animate-fade-in-up" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger-2050 alert-dismissible fade show animate-fade-in-up" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtres -->
    <div class="card-2050 mb-4">
        <div class="card-body p-4">
            <div class="form-row-2050">
                <div class="form-col-2050 col-md-4">
                    <div class="form-group-2050">
                        <label class="form-label-2050">Recherche</label>
                        <input type="text" wire:model.live="search" class="form-control-2050"
                            placeholder="Chauffeur, véhicule...">
                    </div>
                </div>
                <div class="form-col-2050 col-md-3">
                    <div class="form-group-2050">
                        <label class="form-label-2050">Chauffeur</label>
                        <select wire:model.live="chauffeurFilter" class="form-control-2050">
                            <option value="">Tous les chauffeurs</option>
                            @foreach ($chauffeurs as $chauffeur)
                                <option value="{{ $chauffeur->user_id }}">
                                    {{ $chauffeur->nom }} {{ $chauffeur->prenom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-col-2050 col-md-3">
                    <div class="form-group-2050">
                        <label class="form-label-2050">Véhicule</label>
                        <select wire:model.live="vehiculeFilter" class="form-control-2050">
                            <option value="">Tous les véhicules</option>
                            @foreach ($vehicules as $vehicule)
                                <option value="{{ $vehicule->id }}">
                                    {{ $vehicule->immatriculation }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-col-2050 col-md-2">
                    <div class="form-group-2050">
                        <label class="form-label-2050">&nbsp;</label>
                        <button wire:click="resetFilters" class="btn btn-outline-secondary-2050 w-100">
                            <i class="fas fa-refresh me-2"></i>Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des demandes -->
    <div class="card-2050">
        <div class="card-body p-0">
            @if ($demandes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-header-2050">
                            <tr>
                                <th class="text-primary">
                                    <i class="fas fa-user me-2"></i>Chauffeur
                                </th>
                                <th class="text-primary">
                                    <i class="fas fa-car me-2"></i>Véhicule
                                </th>
                                <th class="text-primary">
                                    <i class="fas fa-calendar me-2"></i>Date de début
                                </th>
                                <th class="text-primary">
                                    <i class="fas fa-info-circle me-2"></i>Type
                                </th>
                                <th class="text-primary">
                                    <i class="fas fa-comment me-2"></i>Description
                                </th>
                                <th class="text-primary">
                                    <i class="fas fa-cogs me-2"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($demandes as $demande)
                                <tr class="animate-fade-in-up">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title bg-primary-2050 text-white rounded-circle">
                                                    {{ substr($demande->chauffeur->nom, 0, 1) }}{{ substr($demande->chauffeur->prenom, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $demande->chauffeur->nom }}
                                                    {{ $demande->chauffeur->prenom }}</h6>
                                                <small class="text-muted">{{ $demande->chauffeur->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $demande->vehicule->immatriculation }}</h6>
                                            <small class="text-muted">
                                                {{ $demande->vehicule->marque->nom ?? 'N/A' }}
                                                {{ $demande->vehicule->modele->nom ?? 'N/A' }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $demande->start_date->format('d/m/Y') }}</h6>
                                            <small class="text-muted">{{ $demande->start_date->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-2050">
                                            {{ ucfirst($demande->type_tache) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;"
                                            title="{{ $demande->description }}">
                                            {{ $demande->description ?: 'Aucune description' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button wire:click="valider({{ $demande->id }})"
                                                class="btn btn-success-2050 btn-sm" title="Valider la demande"
                                                onclick="return confirm('Êtes-vous sûr de vouloir valider cette demande ?')">
                                                <i class="fas fa-check me-1"></i>Valider
                                            </button>
                                            <button wire:click="rejeter({{ $demande->id }})"
                                                class="btn btn-danger-2050 btn-sm" title="Rejeter la demande"
                                                onclick="return confirm('Êtes-vous sûr de vouloir rejeter cette demande ?')">
                                                <i class="fas fa-times me-1"></i>Rejeter
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-muted">
                        Affichage de {{ $demandes->firstItem() }} à {{ $demandes->lastItem() }}
                        sur {{ $demandes->total() }} demandes
                    </div>
                    <div>
                        {{ $demandes->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-muted">Aucune demande en attente</h5>
                    <p class="text-muted">Toutes les demandes ont été traitées.</p>
                </div>
            @endif
        </div>
    </div>
</div>
