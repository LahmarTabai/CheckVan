<div>
    <div>
        <div class="d-flex align-items-center mb-4">
            <div class="me-3">
                <div class="glass-effect rounded-circle p-3">
                    <i class="fas fa-map-marked-alt text-gradient fs-4"></i>
                </div>
            </div>
            <div>
                <h1 class="text-gradient mb-0">Carte Interactive</h1>
                <p class="text-muted mb-0">Suivi en temps réel de votre flotte 2050</p>
            </div>
        </div>

        <!-- Contrôles de la carte -->
        <div class="card-2050 mb-4 hover-lift">
            <div class="card-body-2050 p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="map-controls-2050">
                            <div class="d-flex align-items-center gap-3">
                                <div class="control-item-2050">
                                    <i class="fas fa-crosshairs text-primary me-2"></i>
                                    <span>Suivi GPS en temps réel</span>
                                </div>
                                <div class="control-item-2050">
                                    <i class="fas fa-route text-success me-2"></i>
                                    <span>Trajets optimisés</span>
                                </div>
                                <div class="control-item-2050">
                                    <i class="fas fa-shield-alt text-warning me-2"></i>
                                    <span>Sécurité renforcée</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <button wire:click="refresh" class="btn btn-primary-2050">
                            <i class="fas fa-sync-alt me-2"></i>
                            Rafraîchir
                        </button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Carte -->
        <div class="card-2050 hover-lift">
            <div class="card-header-2050">
                <h6 class="mb-0">
                    <i class="fas fa-globe me-2"></i>Carte des Positions
                    <span class="badge badge-primary-2050 ms-2">{{ count($locations) }} Chauffeurs</span>
                </h6>
            </div>
            <div class="card-body p-0">
                <!-- IMPORTANT: empêcher Livewire de remplacer ce nœud -->
                <div wire:ignore id="map" class="map-container-2050"></div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row g-4 mt-4">
            <div class="col-md-4">
                <div class="card-2050 hover-lift">
                    <div class="card-body-2050 text-center p-4">
                        <div class="stat-icon-2050 mb-3">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                        </div>
                        <h4 class="stat-number-2050 mb-1">{{ count($locations) }}</h4>
                        <p class="stat-label-2050 mb-0">Positions enregistrées</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-2050 hover-lift">
                    <div class="card-body-2050 text-center p-4">
                        <div class="stat-icon-2050 mb-3">
                            <i class="fas fa-clock text-success"></i>
                        </div>
                        <h4 class="stat-number-2050 mb-1">Temps réel</h4>
                        <p class="stat-label-2050 mb-0">Mise à jour automatique</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-2050 hover-lift">
                    <div class="card-body-2050 text-center p-4">
                        <div class="stat-icon-2050 mb-3">
                            <i class="fas fa-satellite text-warning"></i>
                        </div>
                        <h4 class="stat-number-2050 mb-1">GPS</h4>
                        <p class="stat-label-2050 mb-0">Précision maximale</p>
                    </div>
                </div>
            </div>
        </div>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <script>
            let map, markersLayer;

            function initMapOnce() {
                // Si déjà initialisée, on ne recrée pas
                if (map) return;

                // Si Leaflet pas encore chargé, réessaye un peu plus tard
                if (!window.L) return setTimeout(initMapOnce, 100);

                const mapEl = document.getElementById('map');
                if (!mapEl) return;

                map = L.map(mapEl, {
                    zoomControl: true,
                    scrollWheelZoom: true,
                    doubleClickZoom: true,
                    boxZoom: true,
                    keyboard: true,
                    dragging: true,
                    touchZoom: true
                }).setView([46.2276, 2.2137], 6);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                }).addTo(map);

                // Couche pour nos marqueurs (facile à vider/remplir)
                markersLayer = L.layerGroup().addTo(map);

                // Premier rendu avec les données serveur initiales
                updateMarkers(@json($locations));

                // Corrige l'affichage si le conteneur a été (re)rendu
                setTimeout(() => map.invalidateSize(), 0);
            }

            function updateMarkers(points) {
                if (!map || !markersLayer) return;
                markersLayer.clearLayers();

                if (!points || !points.length) {
                    const noDataIcon = L.divIcon({
                        className: 'no-data-marker-2050',
                        html: '<div class="no-data-2050"><i class="fas fa-exclamation-triangle"></i><br>Aucune tâche en cours</div>',
                        iconSize: [200, 100],
                        iconAnchor: [100, 50]
                    });
                    L.marker([46.2276, 2.2137], {
                        icon: noDataIcon
                    }).addTo(markersLayer);
                    return;
                }

                const markers = points.map(p => {
                    let color = '#28a745',
                        iconClass = 'fas fa-car';
                    if (p.status === 'en_attente') {
                        color = '#ffc107';
                        iconClass = 'fas fa-clock';
                    } else if (p.status === 'terminée') {
                        color = '#6c757d';
                        iconClass = 'fas fa-check';
                    } else if (p.status === 'disponible') {
                        color = '#007bff';
                        iconClass = 'fas fa-user';
                    }

                    const chauffeurNom = p.chauffeur_nom || 'Chauffeur';
                    const initiales = chauffeurNom.split(' ').map(n => n.charAt(0)).join('').substring(0, 2)
                        .toUpperCase();

                    const customIcon = L.divIcon({
                        className: 'custom-marker-2050',
                        html: `<div class="marker-pulse-2050" style="background-color:${color};border-color:${color};">
                                <span style="color:#fff;font-weight:bold;font-size:12px;">${initiales}</span>
                               </div>`,
                        iconSize: [35, 35],
                        iconAnchor: [17, 17]
                    });

                    const m = L.marker([p.latitude, p.longitude], {
                        icon: customIcon
                    });

                    const statusBadge = p.status === 'en_cours' ? '<span class="badge bg-success">En cours</span>' :
                        p.status === 'en_attente' ? '<span class="badge bg-warning">En attente</span>' :
                        p.status === 'disponible' ? '<span class="badge bg-primary">Disponible</span>' :
                        '<span class="badge bg-secondary">Terminée</span>';

                    m.bindPopup(`
                        <div class="popup-2050">
                            <h6><i class="fas fa-user me-2"></i>${chauffeurNom}</h6>
                            <p><strong>Véhicule:</strong> ${p.vehicule || 'N/A'}</p>
                            <p><strong>Statut:</strong> ${statusBadge}</p>
                            <p><strong>Position:</strong> ${Number(p.latitude).toFixed(6)}, ${Number(p.longitude).toFixed(6)}</p>
                            <p><strong>Dernière mise à jour:</strong> ${new Date(p.recorded_at).toLocaleString('fr-FR')}</p>
                        </div>
                    `);

                    m.addTo(markersLayer);
                    return m;
                });

                // Ajuster la vue
                const group = L.featureGroup(markers);
                if (points.length > 1) {
                    map.fitBounds(group.getBounds(), {
                        padding: [20, 20]
                    });
                }

                // Sécurise l'affichage si le conteneur vient d'être morphé
                setTimeout(() => map.invalidateSize(), 0);
            }

            // Initialisation + écoute des events Livewire
            document.addEventListener('livewire:init', () => {
                initMapOnce();

                // Livewire v3
                Livewire.on('locations-updated', (payload) => {
                    updateMarkers(payload.locations);
                });

                // Si tu utilises la navigation SPA de Livewire
                document.addEventListener('livewire:navigated', initMapOnce);
            });
        </script>

        <style>
            /* Styles pour la carte */
            .map-container-2050 {
                height: 500px !important;
                width: 100% !important;
                border-radius: 0 0 15px 15px;
                overflow: hidden;
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

            .marker-pulse-2050 i {
                font-size: 14px;
                z-index: 2;
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
        </style>
    </div>
</div>
