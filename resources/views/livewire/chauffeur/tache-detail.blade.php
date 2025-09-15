<div>
    <!-- En-tête -->
    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            <div class="glass-effect rounded-circle p-3">
                <i class="fas fa-tasks text-gradient fs-4"></i>
            </div>
        </div>
        <div>
            <h1 class="text-gradient mb-0">Détails de la Tâche</h1>
            <p class="text-muted mb-0">Tâche #{{ $tache->id }} - {{ ucfirst($tache->type_tache) }}</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card-2050 hover-lift">
                <div class="card-header-2050">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations Générales
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="glass-effect p-3 rounded">
                                <h6 class="text-gradient mb-2">Véhicule</h6>
                                <div class="d-flex align-items-center">
                                    <div class="glass-effect rounded-circle p-2 me-3">
                                        <i class="fas fa-car text-gradient"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $tache->vehicule->immatriculation ?? '-' }}</strong><br>
                                        <small class="text-muted">
                                            {{ $tache->vehicule->marque->nom ?? 'N/A' }}
                                            {{ $tache->vehicule->modele->nom ?? 'N/A' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="glass-effect p-3 rounded">
                                <h6 class="text-gradient mb-2">Statut</h6>
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
                                @if ($tache->is_validated)
                                    <span class="badge badge-success-2050 ms-2">
                                        <i class="fas fa-check-circle me-1"></i>Validée
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="glass-effect p-3 rounded">
                                <h6 class="text-gradient mb-2">Type de tâche</h6>
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
                                <span class="badge {{ $typeClasses[$tache->type_tache] ?? 'badge-secondary-2050' }}">
                                    <i class="fas fa-{{ $typeIcons[$tache->type_tache] ?? 'question' }} me-1"></i>
                                    {{ ucfirst($tache->type_tache) }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="glass-effect p-3 rounded">
                                <h6 class="text-gradient mb-2">Dates</h6>
                                <div class="small">
                                    <div><strong>Début:</strong>
                                        {{ $tache->start_date ? $tache->start_date->format('d/m/Y H:i') : '-' }}</div>
                                    <div><strong>Fin:</strong>
                                        {{ $tache->end_date ? $tache->end_date->format('d/m/Y H:i') : '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($tache->description)
                        <div class="mt-4">
                            <h6 class="text-gradient mb-2">Description</h6>
                            <div class="glass-effect p-3 rounded">
                                <p class="mb-0">{{ $tache->description }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kilométrage et carburant -->
            @if ($tache->debut_kilometrage || $tache->fin_kilometrage || $tache->debut_carburant || $tache->fin_carburant)
                <div class="card-2050 hover-lift mt-4">
                    <div class="card-header-2050">
                        <h6 class="mb-0">
                            <i class="fas fa-tachometer-alt me-2"></i>Kilométrage et Carburant
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @if ($tache->debut_kilometrage || $tache->fin_kilometrage)
                                <div class="col-md-6">
                                    <h6 class="text-gradient mb-3">Kilométrage</h6>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="glass-effect p-3 rounded text-center">
                                                <div class="text-muted small">Début</div>
                                                <div class="h5 text-gradient mb-0">
                                                    {{ number_format($tache->debut_kilometrage ?? 0) }} km</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="glass-effect p-3 rounded text-center">
                                                <div class="text-muted small">Fin</div>
                                                <div class="h5 text-gradient mb-0">
                                                    {{ number_format($tache->fin_kilometrage ?? 0) }} km</div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($tache->debut_kilometrage && $tache->fin_kilometrage)
                                        <div class="mt-3 text-center">
                                            <span class="badge badge-info-2050">
                                                <i class="fas fa-route me-1"></i>
                                                {{ number_format($tache->fin_kilometrage - $tache->debut_kilometrage) }}
                                                km parcourus
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if ($tache->debut_carburant || $tache->fin_carburant)
                                <div class="col-md-6">
                                    <h6 class="text-gradient mb-3">Carburant</h6>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="glass-effect p-3 rounded text-center">
                                                <div class="text-muted small">Début</div>
                                                <div class="h5 text-gradient mb-0">{{ $tache->debut_carburant ?? 0 }}%
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="glass-effect p-3 rounded text-center">
                                                <div class="text-muted small">Fin</div>
                                                <div class="h5 text-gradient mb-0">{{ $tache->fin_carburant ?? 0 }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($tache->debut_carburant && $tache->fin_carburant)
                                        <div class="mt-3 text-center">
                                            @php
                                                $consommation = $tache->debut_carburant - $tache->fin_carburant;
                                                $consommationClass =
                                                    $consommation > 0 ? 'badge-warning-2050' : 'badge-success-2050';
                                            @endphp
                                            <span class="badge {{ $consommationClass }}">
                                                <i class="fas fa-gas-pump me-1"></i>
                                                {{ $consommation > 0 ? $consommation . '% consommé' : 'Pas de consommation' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Géolocalisation -->
            @if ($tache->start_latitude || $tache->end_latitude)
                <div class="card-2050 hover-lift mt-4">
                    <div class="card-header-2050">
                        <h6 class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>Géolocalisation
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @if ($tache->start_latitude && $tache->start_longitude)
                                <div class="col-md-6">
                                    <h6 class="text-gradient mb-3">Position de début</h6>
                                    <div class="glass-effect p-3 rounded">
                                        <div class="small">
                                            <div><strong>Latitude:</strong> {{ $tache->start_latitude }}</div>
                                            <div><strong>Longitude:</strong> {{ $tache->start_longitude }}</div>
                                        </div>
                                        <button class="btn btn-sm btn-outline-2050 mt-2"
                                            onclick="openMaps({{ $tache->start_latitude }}, {{ $tache->start_longitude }})">
                                            <i class="fas fa-external-link-alt me-1"></i>Voir sur la carte
                                        </button>
                                    </div>
                                </div>
                            @endif

                            @if ($tache->end_latitude && $tache->end_longitude)
                                <div class="col-md-6">
                                    <h6 class="text-gradient mb-3">Position de fin</h6>
                                    <div class="glass-effect p-3 rounded">
                                        <div class="small">
                                            <div><strong>Latitude:</strong> {{ $tache->end_latitude }}</div>
                                            <div><strong>Longitude:</strong> {{ $tache->end_longitude }}</div>
                                        </div>
                                        <button class="btn btn-sm btn-outline-2050 mt-2"
                                            onclick="openMaps({{ $tache->end_latitude }}, {{ $tache->end_longitude }})">
                                            <i class="fas fa-external-link-alt me-1"></i>Voir sur la carte
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions et informations -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card-2050 hover-lift">
                <div class="card-header-2050">
                    <h6 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        @if ($tache->status === 'en_attente')
                            <a href="{{ route('chauffeur.taches') }}" class="btn btn-primary-2050">
                                <i class="fas fa-play me-2"></i>Démarrer la tâche
                            </a>
                        @elseif($tache->status === 'en_cours')
                            <a href="{{ route('chauffeur.taches') }}" class="btn btn-warning-2050">
                                <i class="fas fa-stop me-2"></i>Terminer la tâche
                            </a>
                        @else
                            <span class="btn btn-success-2050 disabled">
                                <i class="fas fa-check me-2"></i>Tâche terminée
                            </span>
                        @endif

                        <a href="{{ route('chauffeur.taches') }}" class="btn btn-outline-2050">
                            <i class="fas fa-arrow-left me-2"></i>Retour aux tâches
                        </a>
                    </div>
                </div>
            </div>

            <!-- Photos -->
            @if ($tache->photos && $tache->photos->count() > 0)
                <div class="card-2050 hover-lift mt-4">
                    <div class="card-header-2050">
                        <h6 class="mb-0">
                            <i class="fas fa-camera me-2"></i>Photos ({{ $tache->photos->count() }})
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-2">
                            @foreach ($tache->photos as $photo)
                                <div class="col-6">
                                    <img src="{{ Storage::url($photo->path) }}" alt="Photo de la tâche"
                                        class="img-fluid rounded cursor-pointer" style="cursor: pointer;"
                                        data-bs-toggle="modal" data-bs-target="#photoModal{{ $photo->id }}">
                                </div>

                                <!-- Modal pour afficher la photo en grand -->
                                <div class="modal fade" id="photoModal{{ $photo->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Photo - {{ ucfirst($photo->type) }}</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ Storage::url($photo->path) }}" alt="Photo de la tâche"
                                                    class="img-fluid">
                                                @if ($photo->description)
                                                    <p class="mt-3 text-muted">{{ $photo->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Informations système -->
            <div class="card-2050 hover-lift mt-4">
                <div class="card-header-2050">
                    <h6 class="mb-0">
                        <i class="fas fa-info me-2"></i>Informations Système
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="small text-muted">
                        <div><strong>Créée le:</strong> {{ $tache->created_at->format('d/m/Y H:i') }}</div>
                        <div><strong>Modifiée le:</strong> {{ $tache->updated_at->format('d/m/Y H:i') }}</div>
                        @if ($tache->start_date && $tache->end_date)
                            @php
                                $duree = $tache->end_date->diffInMinutes($tache->start_date);
                                $heures = floor($duree / 60);
                                $minutes = $duree % 60;
                            @endphp
                            <div><strong>Durée:</strong> {{ $heures }}h {{ $minutes }}min</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openMaps(lat, lng) {
        const mapsUrl = `https://www.google.com/maps?q=${lat},${lng}`;
        window.open(mapsUrl, '_blank');
    }
</script>
