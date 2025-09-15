<div>
    <div>
        <div class="d-flex align-items-center mb-4">
            <div class="me-3">
                <div class="glass-effect rounded-circle p-3">
                    <i class="fas fa-link text-gradient fs-4"></i>
                </div>
            </div>
            <div>
                <h1 class="text-gradient mb-0">Gestion des Affectations</h1>
                <p class="text-muted mb-0">Système d'affectation intelligent 2050</p>
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
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Règles métier -->
        {{-- <div class="alert alert-info-2050 mb-4">
            <div class="d-flex align-items-start">
                <i class="fas fa-info-circle me-3 mt-1"></i>
                <div>
                    <h6 class="mb-2">Règles d'affectation :</h6>
                    <ul class="mb-0 small">
                        <li>Un chauffeur ne peut avoir qu'un seul véhicule en cours à la fois</li>
                        <li>Un véhicule ne peut être affecté qu'à un seul chauffeur à la fois</li>
                        <li>Lorsqu'une affectation est terminée, le véhicule devient à nouveau disponible</li>
                    </ul>
                </div>
            </div>
        </div> --}}

        <!-- Formulaire Futuriste -->
        <div class="card-2050 mb-4 hover-lift">
            <div class="card-header-2050">
                <h6 class="mb-0">
                    <i class="fas fa-plus me-2"></i>{{ $isEdit ? 'Modifier l\'affectation' : 'Nouvelle affectation' }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'save' }}">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label-2050">Chauffeur *</label>
                            <div class="position-relative">
                                <input type="text" wire:model.live="searchChauffeur"
                                    wire:focus="showChauffeurDropdown = true" class="form-control-2050"
                                    placeholder="Rechercher un chauffeur..." autocomplete="off">

                                @if ($showChauffeurDropdown && $chauffeurs->count() > 0)
                                    <div class="dropdown-menu-2050 show w-100"
                                        style="max-height: 200px; overflow-y: auto;">
                                        @foreach ($chauffeurs as $c)
                                            <div class="dropdown-item-2050"
                                                wire:click="selectChauffeur({{ $c->user_id }}, '{{ $c->nom }} {{ $c->prenom }}')"
                                                style="cursor: pointer;">
                                                {{ $c->nom }} {{ $c->prenom }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if ($chauffeur_id)
                                    <small class="text-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Chauffeur sélectionné
                                    </small>
                                @endif
                            </div>
                            <small class="text-muted">Chauffeurs disponibles : {{ $chauffeurs->count() }}</small>
                            @error('chauffeur_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-2050">Véhicule *</label>
                            <div class="position-relative">
                                <input type="text" wire:model.live="searchVehicule"
                                    wire:focus="showVehiculeDropdown = true" class="form-control-2050"
                                    placeholder="Rechercher un véhicule..." autocomplete="off">

                                @if ($showVehiculeDropdown && $vehicules->count() > 0)
                                    <div class="dropdown-menu-2050 show w-100"
                                        style="max-height: 200px; overflow-y: auto;">
                                        @foreach ($vehicules as $v)
                                            <div class="dropdown-item-2050"
                                                wire:click="selectVehicule({{ $v->id }}, '{{ $v->marque->nom ?? 'N/A' }} {{ $v->modele->nom ?? '' }} - {{ $v->immatriculation }}')"
                                                style="cursor: pointer;">
                                                {{ $v->marque->nom ?? 'N/A' }} {{ $v->modele->nom ?? '' }} -
                                                {{ $v->immatriculation }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if ($vehicule_id)
                                    <small class="text-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Véhicule sélectionné
                                    </small>
                                @endif
                            </div>
                            <small class="text-muted">Véhicules disponibles : {{ $vehicules->count() }}</small>
                            @error('vehicule_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-2050">Statut</label>
                            <select wire:model="status" class="form-control-2050 select2-2050">
                                <option value="en_cours">En cours</option>
                                <option value="terminée">Terminée</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-2050">Date de début *</label>
                            <input type="date" wire:model="date_debut" class="form-control-2050">
                            @error('date_debut')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-2050">Date de fin</label>
                            <input type="date" wire:model="date_fin" class="form-control-2050">
                            @error('date_fin')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label-2050">Description</label>
                            <textarea wire:model="description" class="form-control-2050" rows="3"
                                placeholder="Description de l'affectation..."></textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary-2050 me-3">
                                <i class="fas fa-save me-2"></i>{{ $isEdit ? 'Modifier' : 'Ajouter' }} Affectation
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

        <!-- Filtres Futuristes -->
        <div class="card-2050 mb-4 hover-lift">
            <div class="card-header-2050">
                <h6 class="mb-0">
                    <i class="fas fa-filter me-2"></i>Filtres Intelligents
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label-2050">Statut</label>
                        <select wire:model.live="filterStatus" class="form-control-2050 select2-2050">
                            <option value="">Tous</option>
                            <option value="en_cours">En cours</option>
                            <option value="terminée">Terminée</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-2050">Chauffeur</label>
                        <select wire:model.live="filterChauffeur" class="form-control-2050 select2-2050">
                            <option value="">Tous</option>
                            @foreach ($chauffeurs as $c)
                                <option value="{{ $c->user_id }}">{{ $c->nom }} {{ $c->prenom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-2050">&nbsp;</label>
                        <button wire:click="resetFilters" class="btn btn-outline-2050 w-100">
                            <i class="fas fa-times me-2"></i>Effacer les filtres
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des affectations Futuriste -->
        <div class="card-2050 hover-lift">
            <div class="card-header-2050">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Liste des affectations
                        <span class="badge badge-success-2050 ms-2">{{ $affectations->total() }}</span>
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
                                    <button wire:click="sortBy('date_debut')"
                                        class="btn btn-link p-0 text-decoration-none text-primary">
                                        <i class="fas fa-calendar me-2"></i>Dates
                                        @if ($sortField === 'date_debut')
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
                                <th class="text-primary"><i class="fas fa-file-text me-2"></i>Description</th>
                                <th class="text-primary"><i class="fas fa-cogs me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($affectations as $a)
                                <tr class="animate-fade-in-up">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="glass-effect rounded-circle p-2 me-3">
                                                <i class="fas fa-user text-gradient"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $a->chauffeur->nom ?? '-' }}
                                                    {{ $a->chauffeur->prenom ?? '' }}</strong>
                                                @if ($a->chauffeur)
                                                    <br><small class="text-muted">{{ $a->chauffeur->email }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="glass-effect rounded-circle p-2 me-3">
                                                <i class="fas fa-car text-gradient"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $a->vehicule->marque->nom ?? 'N/A' }}
                                                    {{ $a->vehicule->modele->nom ?? '' }}</strong>
                                                <br><small
                                                    class="text-muted">{{ $a->vehicule->immatriculation ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small>
                                            <strong>Début:</strong>
                                            {{ $a->date_debut ? \Carbon\Carbon::parse($a->date_debut)->format('d/m/Y') : '-' }}<br>
                                            <strong>Fin:</strong>
                                            {{ $a->date_fin ? \Carbon\Carbon::parse($a->date_fin)->format('d/m/Y') : 'Non définie' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if ($a->status === 'en_cours')
                                            <span class="badge badge-warning-2050">
                                                <i class="fas fa-clock me-1"></i>{{ ucfirst($a->status) }}
                                            </span>
                                        @else
                                            <span class="badge badge-success-2050">
                                                <i class="fas fa-check me-1"></i>{{ ucfirst($a->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($a->description)
                                            <small>{{ Str::limit($a->description, 50) }}</small>
                                        @else
                                            <small class="text-muted">Aucune description</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group-actions">
                                            @if ($a->status === 'en_cours')
                                                <button wire:click="terminerAffectation({{ $a->id }})"
                                                    class="btn btn-success-2050 btn-sm" title="Terminer l'affectation"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir terminer cette affectation ?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <button wire:click="edit({{ $a->id }})"
                                                class="btn btn-warning-2050 btn-sm" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="delete({{ $a->id }})"
                                                class="btn btn-danger-2050 btn-sm"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette affectation ?')"
                                                title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <div class="glass-effect rounded-circle p-4 mx-auto mb-3"
                                            style="width: 80px; height: 80px;">
                                            <i class="fas fa-tasks text-gradient fs-2"></i>
                                        </div>
                                        <h5>Aucune affectation trouvée</h5>
                                        <p class="mb-0">Créez votre première affectation pour commencer</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $affectations->links() }}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fermer les dropdowns quand on clique ailleurs
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.position-relative')) {
                Livewire.dispatch('hideDropdowns');
            }
        });
    });
</script>
