<div>
    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            <div class="glass-effect rounded-circle p-3">
                <i class="fas fa-map-marked-alt text-gradient fs-4"></i>
            </div>
        </div>
        <div>
            <h1 class="text-gradient mb-0">Carte Interactive</h1>
            <p class="text-muted mb-0">
                Suivi en temps réel de votre flotte 2050
                @if ($kpis['derniere_maj'])
                    <span class="badge bg-secondary ms-2">
                        <i class="fas fa-clock me-1"></i>Dernière MAJ: {{ $kpis['derniere_maj'] }}
                    </span>
                @endif
            </p>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="kpi-card-2050 kpi-primary">
                <div class="kpi-icon"><i class="fas fa-users"></i></div>
                <div class="kpi-content">
                    <div class="kpi-value">{{ $kpis['total'] }}</div>
                    <div class="kpi-label">Total Chauffeurs</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card-2050 kpi-success">
                <div class="kpi-icon"><i class="fas fa-car"></i></div>
                <div class="kpi-content">
                    <div class="kpi-value">{{ $kpis['en_cours'] }}</div>
                    <div class="kpi-label">En Course</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card-2050 kpi-info">
                <div class="kpi-icon"><i class="fas fa-user-check"></i></div>
                <div class="kpi-content">
                    <div class="kpi-value">{{ $kpis['disponible'] }}</div>
                    <div class="kpi-label">Disponibles</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card-2050 kpi-secondary">
                <div class="kpi-icon"><i class="fas fa-wifi-slash"></i></div>
                <div class="kpi-content">
                    <div class="kpi-value">{{ $kpis['hors_ligne'] }}</div>
                    <div class="kpi-label">Hors Ligne</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contrôles et Filtres -->
    <div class="card-2050 mb-4 hover-lift">
        <div class="card-body-2050 p-4">
            <div class="row g-3 align-items-end">
                <!-- Recherche -->
                <div class="col-md-3">
                    <label class="form-label-2050">
                        <i class="fas fa-search me-2"></i>Rechercher
                    </label>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-2050"
                        placeholder="Nom du chauffeur...">
                </div>

                <!-- Sélection chauffeur -->
                <div class="col-md-3">
                    <label class="form-label-2050">
                        <i class="fas fa-user me-2"></i>Chauffeur
                    </label>
                    <select wire:model.live="chauffeurFiltre" class="form-select form-select-2050">
                        <option value="">🚗 Tous les chauffeurs</option>
                        @foreach ($chauffeurs as $chauffeur)
                            <option value="{{ $chauffeur->user_id }}">
                                {{ $chauffeur->nom }} {{ $chauffeur->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtres de statut -->
                <div class="col-md-4">
                    <label class="form-label-2050">
                        <i class="fas fa-filter me-2"></i>Filtrer par statut
                    </label>
                    <div class="d-flex gap-3">
                        <div class="form-check form-check-2050">
                            <input class="form-check-input" type="checkbox" wire:model.live="filterEnCours"
                                id="filterEnCours">
                            <label class="form-check-label" for="filterEnCours">
                                <i class="fas fa-car text-success"></i> En cours
                            </label>
                        </div>
                        <div class="form-check form-check-2050">
                            <input class="form-check-input" type="checkbox" wire:model.live="filterDisponible"
                                id="filterDisponible">
                            <label class="form-check-label" for="filterDisponible">
                                <i class="fas fa-user-check text-info"></i> Dispo
                            </label>
                        </div>
                        <div class="form-check form-check-2050">
                            <input class="form-check-input" type="checkbox" wire:model.live="filterHorsLigne"
                                id="filterHorsLigne">
                            <label class="form-check-label" for="filterHorsLigne">
                                <i class="fas fa-wifi-slash text-secondary"></i> Hors ligne
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button wire:click="toggleAutoRefresh"
                            class="btn {{ $autoRefresh ? 'btn-success-2050' : 'btn-outline-secondary' }} btn-sm flex-grow-1"
                            title="Auto-refresh toutes les 15s">
                            <i class="fas fa-sync-alt {{ $autoRefresh ? 'fa-spin' : '' }}"></i>
                        </button>
                        <button wire:click="refresh" wire:loading.attr="disabled" wire:target="refresh"
                            class="btn btn-primary-2050 btn-sm flex-grow-1">
                            <span wire:loading.remove wire:target="refresh">
                                <i class="fas fa-sync-alt me-1"></i>MAJ
                            </span>
                            <span wire:loading wire:target="refresh">
                                <i class="fas fa-circle-notch fa-spin me-1"></i>Chargement...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteneur avec auto-refresh -->
    <div @if ($autoRefresh) wire:poll.15s="refresh" @endif>
        <div class="row">
            <!-- Carte (col principale) -->
            <div class="col-lg-9">
                <div class="card-2050 hover-lift">
                    <div class="card-header-2050">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-globe me-2"></i>Carte des Positions
                                <span class="badge badge-primary-2050 ms-2">
                                    {{ count($locations) }} Position(s)
                                </span>
                                @if ($chauffeurFiltre || $search)
                                    <span class="badge bg-info ms-2">
                                        <i class="fas fa-filter me-1"></i>Filtré
                                    </span>
                                @endif
                            </h6>
                            <div class="d-flex gap-2">
                                <!-- Toggle Mode Sombre -->
                                <button onclick="toggleDarkMode()" class="btn btn-sm btn-outline-light"
                                    title="Mode sombre" id="darkModeBtn">
                                    <i class="fas fa-moon"></i>
                                </button>
                                <!-- Bouton Plein écran -->
                                <button onclick="toggleFullscreen()" class="btn btn-sm btn-outline-light"
                                    title="Plein écran" id="fullscreenBtn">
                                    <i class="fas fa-expand"></i>
                                </button>
                                @if ($chauffeurFiltre || $search)
                                    <button wire:click="$set('chauffeurFiltre', null); $set('search', '')"
                                        class="btn btn-sm btn-outline-light" title="Réinitialiser les filtres">
                                        <i class="fas fa-times me-1"></i>Réinitialiser
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <!-- IMPORTANT: empêcher Livewire de remplacer ce nœud -->
                        <div wire:ignore id="map" class="map-container-2050" data-fullscreen="false"></div>
                    </div>
                </div>
            </div>

            <!-- Panneau latéral - Liste des chauffeurs -->
            <div class="col-lg-3">
                <div class="card-2050">
                    <div class="card-header-2050">
                        <h6 class="mb-0">
                            <i class="fas fa-list me-2"></i>Liste des Chauffeurs
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="chauffeur-list-2050">
                            @forelse($locations as $location)
                                <div class="chauffeur-item-2050"
                                    onclick="focusOnChauffeur({{ $location['latitude'] }}, {{ $location['longitude'] }}, '{{ $location['chauffeur_nom'] }}')">
                                    <div class="chauffeur-avatar-2050 status-{{ $location['status'] }}">
                                        {{ substr($location['chauffeur_nom'], 0, 2) }}
                                    </div>
                                    <div class="chauffeur-info-2050">
                                        <div class="chauffeur-name-2050">{{ $location['chauffeur_nom'] }}</div>
                                        <div class="chauffeur-vehicle-2050">{{ $location['vehicule'] }}</div>
                                        <div class="chauffeur-status-2050">
                                            @if ($location['status'] === 'en_cours')
                                                <span class="badge bg-success">En cours</span>
                                            @elseif($location['status'] === 'disponible')
                                                <span class="badge bg-info">Disponible</span>
                                            @else
                                                <span class="badge bg-secondary">Hors ligne</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="chauffeur-action-2050">
                                        <i class="fas fa-crosshairs"></i>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center p-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Aucun chauffeur à afficher</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
        <script src="{{ asset('js/advanced-map.js') }}"></script>
        <script>
            // Utilisation du service avancé
            document.addEventListener('livewire:init', () => {
                // Initialiser la carte avec les données serveur
                window.advancedMapService.init(@json($locations));

                // Écouter les mises à jour Livewire
                Livewire.on('locations-updated', (payload) => {
                    window.advancedMapService.updateMarkers(payload.locations);
                });

                // Support navigation SPA
                document.addEventListener('livewire:navigated', () => {
                    window.advancedMapService.init(@json($locations));
                });
            });
        </script>

        <style>
            /* KPI Cards */
            .kpi-card-2050 {
                background: linear-gradient(135deg, var(--kpi-color-start), var(--kpi-color-end));
                border-radius: 15px;
                padding: 1.5rem;
                display: flex;
                align-items: center;
                gap: 1rem;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .kpi-card-2050:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            }

            .kpi-primary {
                --kpi-color-start: #667eea;
                --kpi-color-end: #764ba2;
            }

            .kpi-success {
                --kpi-color-start: #11998e;
                --kpi-color-end: #38ef7d;
            }

            .kpi-info {
                --kpi-color-start: #2193b0;
                --kpi-color-end: #6dd5ed;
            }

            .kpi-secondary {
                --kpi-color-start: #757F9A;
                --kpi-color-end: #D7DDE8;
            }

            .kpi-icon {
                width: 60px;
                height: 60px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                color: white;
            }

            .kpi-content {
                flex: 1;
                color: white;
            }

            .kpi-value {
                font-size: 2rem;
                font-weight: bold;
                line-height: 1;
                margin-bottom: 0.25rem;
            }

            .kpi-label {
                font-size: 0.9rem;
                opacity: 0.9;
            }

            /* Formulaires */
            .form-label-2050 {
                font-weight: 600;
                color: #2d3748;
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
            }

            .form-control-2050 {
                border: 2px solid #e2e8f0;
                border-radius: 10px;
                padding: 0.6rem 1rem;
                transition: all 0.3s ease;
            }

            .form-control-2050:focus {
                outline: none;
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            }

            .form-select-2050 {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: 2px solid rgba(255, 255, 255, 0.2);
                color: white;
                font-weight: 500;
                padding: 0.6rem 2.5rem 0.6rem 1rem;
                border-radius: 10px;
                cursor: pointer;
                transition: all 0.3s ease;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='white' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right 0.75rem center;
                background-size: 16px 12px;
            }

            .form-select-2050:focus {
                outline: none;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.4);
                border-color: rgba(255, 255, 255, 0.4);
            }

            .form-select-2050 option {
                background-color: #2d3748;
                color: white;
                padding: 10px;
            }

            .form-check-2050 {
                padding-left: 1.5rem;
            }

            .form-check-2050 .form-check-input {
                width: 1.2rem;
                height: 1.2rem;
                margin-top: 0.1rem;
                cursor: pointer;
            }

            .form-check-2050 .form-check-label {
                cursor: pointer;
                font-size: 0.9rem;
            }

            /* Styles pour la carte */
            .map-container-2050 {
                height: 600px !important;
                width: 100% !important;
                border-radius: 0 0 15px 15px;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            /* Plein écran */
            .fullscreen-map {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                z-index: 9999 !important;
                border-radius: 0 !important;
                margin: 0 !important;
            }

            /* Popup amélioré */
            .popup-2050 {
                min-width: 250px;
            }

            .popup-2050 h6 {
                color: #2d3748;
                border-bottom: 2px solid #667eea;
                padding-bottom: 0.5rem;
                margin-bottom: 0.75rem;
            }

            .popup-2050 p {
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
            }

            .popup-actions {
                border-top: 1px solid #e2e8f0;
                padding-top: 0.75rem;
            }

            .popup-actions .btn {
                flex: 1;
            }

            /* Marqueurs */
            .custom-marker-2050 {
                background: transparent !important;
                border: none !important;
            }

            .marker-pulse-2050 {
                width: 35px;
                height: 35px;
                border-radius: 50%;
                border: 3px solid;
                display: flex;
                align-items: center;
                justify-content: center;
                animation: pulse-2050 2s infinite;
                box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.7);
                position: relative;
            }

            .marker-pulse-2050.stale {
                opacity: 0.6;
                animation: none;
            }

            @keyframes pulse-2050 {
                0% {
                    box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.7);
                }

                70% {
                    box-shadow: 0 0 0 10px rgba(0, 0, 0, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
                }
            }

            /* Panneau latéral - Liste des chauffeurs */
            .chauffeur-list-2050 {
                max-height: 600px;
                overflow-y: auto;
            }

            .chauffeur-item-2050 {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1rem;
                border-bottom: 1px solid #e2e8f0;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .chauffeur-item-2050:hover {
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            }

            .chauffeur-avatar-2050 {
                width: 45px;
                height: 45px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                color: white;
                font-size: 14px;
            }

            .chauffeur-avatar-2050.status-en_cours {
                background: linear-gradient(135deg, #11998e, #38ef7d);
            }

            .chauffeur-avatar-2050.status-disponible {
                background: linear-gradient(135deg, #2193b0, #6dd5ed);
            }

            .chauffeur-avatar-2050.status-hors_ligne {
                background: linear-gradient(135deg, #757F9A, #D7DDE8);
            }

            .chauffeur-info-2050 {
                flex: 1;
            }

            .chauffeur-name-2050 {
                font-weight: 600;
                color: #2d3748;
                margin-bottom: 0.25rem;
            }

            .chauffeur-vehicle-2050 {
                font-size: 0.85rem;
                color: #718096;
                margin-bottom: 0.25rem;
            }

            .chauffeur-action-2050 {
                color: #667eea;
                font-size: 1.2rem;
            }

            /* Scrollbar personnalisée */
            .chauffeur-list-2050::-webkit-scrollbar {
                width: 6px;
            }

            .chauffeur-list-2050::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            .chauffeur-list-2050::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #667eea, #764ba2);
                border-radius: 10px;
            }

            .chauffeur-list-2050::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, #764ba2, #667eea);
            }
        </style>
    @endpush
