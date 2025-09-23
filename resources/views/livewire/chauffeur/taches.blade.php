<div class="container-fluid py-4">
    {{-- Messages Flash --}}
    @if (session()->has('success'))
        <div class="alert alert-success-2050 alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger-2050 alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-gradient mb-1">
                <i class="fas fa-tasks me-3"></i>Mes Tâches
            </h2>
            <p class="text-muted mb-0">Gérez vos tâches et suivez votre progression</p>
        </div>
        <div class="header-stats-2050">
            <div class="stat-item-2050">
                <i class="fas fa-clock text-warning"></i>
                <span>{{ $taches->where('status', 'en_attente')->count() }}</span>
                <small>En attente</small>
            </div>
            <div class="stat-item-2050">
                <i class="fas fa-play text-info"></i>
                <span>{{ $taches->where('status', 'en_cours')->count() }}</span>
                <small>En cours</small>
            </div>
            <div class="stat-item-2050">
                <i class="fas fa-check text-success"></i>
                <span>{{ $taches->where('status', 'terminée')->count() }}</span>
                <small>Terminées</small>
            </div>
        </div>
    </div>

    {{-- Liste des Tâches --}}
    <div class="row g-4">
        @forelse($taches as $tache)
            <div class="col-lg-6 col-xl-4">
                <div class="card-2050 hover-lift h-100">
                    <div class="card-header-2050">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">
                                    <i
                                        class="fas fa-{{ $tache->type_tache === 'maintenance' ? 'wrench' : ($tache->type_tache === 'livraison' ? 'truck' : ($tache->type_tache === 'inspection' ? 'search' : 'question')) }} me-2"></i>
                                    {{ ucfirst($tache->type_tache) }}
                                </h6>
                                <small class="text-muted">Tâche #{{ $tache->id }}</small>
                            </div>
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
                            <span class="badge {{ $statusClasses[$tache->status] ?? 'badge-secondary-2050' }}">
                                <i class="fas fa-{{ $statusIcons[$tache->status] ?? 'question' }} me-1"></i>
                                {{ ucfirst($tache->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        {{-- Informations Véhicule --}}
                        <div class="info-section-2050 mb-3">
                            <div class="d-flex align-items-center mb-2">
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
                        </div>

                        {{-- Dates --}}
                        <div class="info-section-2050 mb-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted">Début</small>
                                    <div class="fw-bold">
                                        {{ $tache->start_date ? $tache->start_date->format('d/m/Y H:i') : '-' }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Fin</small>
                                    <div class="fw-bold">
                                        {{ $tache->end_date ? $tache->end_date->format('d/m/Y H:i') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kilométrage et Carburant --}}
                        @if ($tache->debut_kilometrage || $tache->fin_kilometrage)
                            <div class="info-section-2050 mb-3">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <small class="text-muted">Kilométrage</small>
                                        <div class="fw-bold">
                                            @if ($tache->debut_kilometrage && $tache->fin_kilometrage)
                                                {{ number_format($tache->debut_kilometrage) }} →
                                                {{ number_format($tache->fin_kilometrage) }}
                                                <small
                                                    class="text-success">(+{{ $tache->fin_kilometrage - $tache->debut_kilometrage }}
                                                    km)</small>
                                            @elseif($tache->debut_kilometrage)
                                                {{ number_format($tache->debut_kilometrage) }} km
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Carburant</small>
                                        <div class="fw-bold">
                                            @if ($tache->debut_carburant && $tache->fin_carburant)
                                                {{ $tache->debut_carburant }}% → {{ $tache->fin_carburant }}%
                                                <small
                                                    class="text-{{ $tache->fin_carburant < $tache->debut_carburant ? 'danger' : 'success' }}">
                                                    ({{ $tache->fin_carburant - $tache->debut_carburant > 0 ? '+' : '' }}{{ $tache->fin_carburant - $tache->debut_carburant }}%)
                                                </small>
                                            @elseif($tache->debut_carburant)
                                                {{ $tache->debut_carburant }}%
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Description --}}
                        @if ($tache->description)
                            <div class="info-section-2050 mb-3">
                                <small class="text-muted">Description</small>
                                <div class="fw-bold">{{ Str::limit($tache->description, 100) }}</div>
                            </div>
                        @endif

                        {{-- Validation --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <small class="text-muted">Validation</small>
                                <div>
                                    @if ($tache->is_validated)
                                        <span class="badge badge-success-2050">
                                            <i class="fas fa-check me-1"></i>Validée
                                        </span>
                                    @else
                                        <span class="badge badge-warning-2050">
                                            <i class="fas fa-clock me-1"></i>En attente
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if ($tache->photos->count() > 0)
                                <div>
                                    <small class="text-muted">Photos</small>
                                    <div>
                                        <span class="badge badge-info-2050">
                                            <i class="fas fa-camera me-1"></i>{{ $tache->photos->count() }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-2">
                            @if ($tache->status === 'en_attente')
                                <button wire:click="showStartModal({{ $tache->id }})"
                                    class="btn btn-primary-2050 flex-fill"
                                    @if (!$tache->is_validated) disabled title="Tâche non validée" @endif>
                                    <i class="fas fa-play me-2"></i>Démarrer
                                </button>
                            @elseif($tache->status === 'en_cours')
                                <button wire:click="showEndModal({{ $tache->id }})"
                                    class="btn btn-success-2050 flex-fill">
                                    <i class="fas fa-stop me-2"></i>Terminer
                                </button>
                            @else
                                <button class="btn btn-outline-2050 flex-fill" disabled>
                                    <i class="fas fa-check me-2"></i>Terminée
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card-2050 text-center py-5">
                    <div class="empty-state-2050">
                        <i class="fas fa-tasks text-muted mb-3" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mb-2">Aucune tâche</h4>
                        <p class="text-muted">Vous n'avez aucune tâche assignée pour le moment.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Modal Démarrer Tâche --}}
    @if ($showStartModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content-2050">
                    <div class="modal-header-2050">
                        <h5 class="modal-title">
                            <i class="fas fa-play me-2"></i>Démarrer la Tâche
                        </h5>
                        <button type="button" class="btn-close-2050" wire:click="closeStartModal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <form wire:submit.prevent="startTache">
                            {{-- Informations Véhicule --}}
                            <div class="info-card-2050 mb-4">
                                <h6><i class="fas fa-car me-2"></i>Véhicule</h6>
                                <div class="d-flex align-items-center">
                                    <div class="glass-effect rounded-circle p-2 me-3">
                                        <i class="fas fa-car text-gradient"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $selectedTache->vehicule->immatriculation ?? '-' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $selectedTache->vehicule->marque->nom ?? '-' }}
                                            {{ $selectedTache->vehicule->modele->nom ?? '-' }}</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Kilométrage et Carburant --}}
                            <div class="form-section-2050 mb-4">
                                <h6 class="section-title-2050">
                                    <i class="fas fa-tachometer-alt me-2"></i>Données véhicule
                                </h6>

                                <div class="form-row-2050">
                                    <div class="form-col-2050 col-md-6">
                                        <div class="form-group-2050">
                                            <label class="form-label-2050">
                                                Kilométrage de début <span class="required">*</span>
                                            </label>
                                            <input type="number" wire:model="debut_kilometrage"
                                                class="form-control-2050" placeholder="Ex: 50000" min="0"
                                                required>
                                            <small class="form-help-2050">Kilométrage actuel du véhicule</small>
                                            @error('debut_kilometrage')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-col-2050 col-md-6">
                                        <div class="form-group-2050">
                                            <label class="form-label-2050">
                                                Carburant de début (%) <span class="required">*</span>
                                            </label>
                                            <input type="number" wire:model="debut_carburant"
                                                class="form-control-2050" placeholder="Ex: 75" min="0"
                                                max="100" step="0.1" required>
                                            <small class="form-help-2050">Niveau de carburant actuel</small>
                                            @error('debut_carburant')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Géolocalisation --}}
                            <div class="form-section-2050 mb-4">
                                <h6 class="section-title-2050">
                                    <i class="fas fa-map-marker-alt me-2"></i>Position GPS
                                </h6>

                                <div class="form-row-2050">
                                    <div class="form-col-2050 col-md-6">
                                        <div class="form-group-2050">
                                            <label class="form-label-2050">
                                                Latitude <span class="required">*</span>
                                            </label>
                                            <input type="number" wire:model="start_latitude"
                                                class="form-control-2050" step="any" placeholder="Ex: 48.8566"
                                                required>
                                            <small class="form-help-2050">Position GPS automatique</small>
                                            @error('start_latitude')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-col-2050 col-md-6">
                                        <div class="form-group-2050">
                                            <label class="form-label-2050">
                                                Longitude <span class="required">*</span>
                                            </label>
                                            <input type="number" wire:model="start_longitude"
                                                class="form-control-2050" step="any" placeholder="Ex: 2.3522"
                                                required>
                                            <small class="form-help-2050">Position GPS automatique</small>
                                            @error('start_longitude')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Photos --}}
                            <div class="form-section-2050 mb-4">
                                <h6 class="section-title-2050">
                                    <i class="fas fa-camera me-2"></i>Photos de début
                                </h6>

                                <div class="form-row-2050">
                                    <div class="form-col-2050 col-12">
                                        <div class="form-group-2050">
                                            <label class="form-label-2050">
                                                Photos de début <span class="required">*</span>
                                            </label>
                                            <input type="file" wire:model="start_photos" class="form-control-2050"
                                                multiple accept="image/*" required>
                                            <small class="form-help-2050">Prenez au moins 3 photos : kilométrage,
                                                carburant, plaque d'immatriculation</small>
                                            @error('start_photos')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            @error('start_photos.*')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Boutons --}}
                            <div class="form-actions-2050">
                                <button type="submit" class="btn btn-primary-2050">
                                    <i class="fas fa-play me-2"></i>Démarrer la Tâche
                                </button>
                                <button type="button" wire:click="closeStartModal" class="btn btn-outline-2050">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Terminer Tâche --}}
    @if ($showEndModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content-2050">
                    <div class="modal-header-2050">
                        <h5 class="modal-title">
                            <i class="fas fa-stop me-2"></i>Terminer la Tâche
                        </h5>
                        <button type="button" class="btn-close-2050" wire:click="closeEndModal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <form wire:submit.prevent="endTache">
                            {{-- Informations Véhicule --}}
                            <div class="info-card-2050 mb-4">
                                <h6><i class="fas fa-car me-2"></i>Véhicule</h6>
                                <div class="d-flex align-items-center">
                                    <div class="glass-effect rounded-circle p-2 me-3">
                                        <i class="fas fa-car text-gradient"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $selectedTache->vehicule->immatriculation ?? '-' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $selectedTache->vehicule->marque->nom ?? '-' }}
                                            {{ $selectedTache->vehicule->modele->nom ?? '-' }}</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Kilométrage et Carburant --}}
                            <div class="form-section-2050 mb-4">
                                <h6 class="section-title-2050">
                                    <i class="fas fa-tachometer-alt me-2"></i>Données véhicule finales
                                </h6>

                                <div class="form-row-2050">
                                    <div class="form-col-2050 col-md-6">
                                        <div class="form-group-2050">
                                            <label class="form-label-2050">
                                                Kilométrage de fin <span class="required">*</span>
                                            </label>
                                            <input type="number" wire:model="fin_kilometrage"
                                                class="form-control-2050" placeholder="Ex: 50150" min="0"
                                                required>
                                            <small class="form-help-2050">Kilométrage final du véhicule</small>
                                            @error('fin_kilometrage')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-col-2050 col-md-6">
                                        <div class="form-group-2050">
                                            <label class="form-label-2050">
                                                Carburant de fin (%) <span class="required">*</span>
                                            </label>
                                            <input type="number" wire:model="fin_carburant"
                                                class="form-control-2050" placeholder="Ex: 70" min="0"
                                                max="100" step="0.1" required>
                                            <small class="form-help-2050">Niveau de carburant final</small>
                                            @error('fin_carburant')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Géolocalisation --}}
                            <div class="form-section-2050 mb-4">
                                <h6 class="section-title-2050">
                                    <i class="fas fa-map-marker-alt me-2"></i>Position GPS finale
                                </h6>

                                <div class="form-row-2050">
                                    <div class="form-col-2050 col-md-6">
                                        <div class="form-group-2050">
                                            <label class="form-label-2050">
                                                Latitude <span class="required">*</span>
                                            </label>
                                            <input type="number" wire:model="end_latitude" class="form-control-2050"
                                                step="any" placeholder="Ex: 48.8566" required>
                                            <small class="form-help-2050">Position GPS automatique</small>
                                            @error('end_latitude')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-col-2050 col-md-6">
                                        <div class="form-group-2050">
                                            <label class="form-label-2050">
                                                Longitude <span class="required">*</span>
                                            </label>
                                            <input type="number" wire:model="end_longitude"
                                                class="form-control-2050" step="any" placeholder="Ex: 2.3522"
                                                required>
                                            <small class="form-help-2050">Position GPS automatique</small>
                                            @error('end_longitude')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Photos --}}
                            <div class="form-section-2050 mb-4">
                                <h6 class="section-title-2050">
                                    <i class="fas fa-camera me-2"></i>Photos de fin
                                </h6>

                                <div class="form-row-2050">
                                    <div class="form-col-2050 col-12">
                                        <div class="form-group-2050">
                                            <label class="form-label-2050">
                                                Photos de fin <span class="required">*</span>
                                            </label>
                                            <input type="file" wire:model="end_photos" class="form-control-2050"
                                                multiple accept="image/*" required>
                                            <small class="form-help-2050">Prenez au moins 3 photos : kilométrage,
                                                carburant, plaque d'immatriculation</small>
                                            @error('end_photos')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            @error('end_photos.*')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="form-section-2050 mb-4">
                                <h6 class="section-title-2050">
                                    <i class="fas fa-align-left me-2"></i>Description de fin
                                </h6>

                                <div class="form-row-2050">
                                    <div class="form-col-2050 col-12">
                                        <div class="form-group-2050">
                                            <label class="form-label-2050">Description de fin</label>
                                            <textarea wire:model="description_fin" class="form-control-2050" rows="3"
                                                placeholder="Décrivez les détails de la tâche terminée..."></textarea>
                                            <small class="form-help-2050">Décrivez ce qui a été fait pendant cette
                                                tâche</small>
                                            @error('description_fin')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Boutons --}}
                            <div class="form-actions-2050">
                                <button type="submit" class="btn btn-success-2050">
                                    <i class="fas fa-stop me-2"></i>Terminer la Tâche
                                </button>
                                <button type="button" wire:click="closeEndModal" class="btn btn-outline-2050">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    // Géolocalisation automatique
    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    @this.set('start_latitude', position.coords.latitude);
                    @this.set('start_longitude', position.coords.longitude);
                },
                function(error) {
                    console.error('Erreur de géolocalisation:', error);
                }
            );
        }
    }

    // Auto-géolocalisation quand on ouvre les modales
    document.addEventListener('livewire:init', () => {
        Livewire.on('showStartModal', () => {
            setTimeout(getCurrentLocation, 500);
        });

        Livewire.on('showEndModal', () => {
            setTimeout(getCurrentLocation, 500);
        });
    });
</script>
