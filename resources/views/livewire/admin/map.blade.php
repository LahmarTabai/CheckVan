
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
                    <span class="badge badge-primary-2050 ms-2">{{ count($locations) }} Points</span>
                </h6>
            </div>
            <div class="card-body p-0">
                <div id="map" class="map-container-2050"></div>
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

        <script>
            document.addEventListener('livewire:navigated', initMap);
            document.addEventListener('livewire:init', initMap);

            function initMap() {
                if (!window.L) return;
                const mapEl = document.getElementById('map');
                if (!mapEl) return;

                // Créer la carte avec un style futuriste
                const map = L.map('map', {
                    zoomControl: true,
                    scrollWheelZoom: true,
                    doubleClickZoom: true,
                    boxZoom: true,
                    keyboard: true,
                    dragging: true,
                    touchZoom: true
                }).setView([46.2276, 2.2137], 6); // Centre sur la France

                // Ajouter une couche de tuiles avec style sombre
                L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                    subdomains: 'abcd',
                    maxZoom: 19
                }).addTo(map);

                // Ajouter des marqueurs personnalisés
                const points = @json($locations);
                if (points.length) {
                    const customIcon = L.divIcon({
                        className: 'custom-marker-2050',
                        html: '<div class="marker-pulse-2050"><i class="fas fa-car"></i></div>',
                        iconSize: [30, 30],
                        iconAnchor: [15, 15]
                    });

                    const markers = points.map(p => {
                        const marker = L.marker([p.latitude, p.longitude], {
                            icon: customIcon
                        });
                        marker.bindPopup(`
                            <div class="popup-2050">
                                <h6><i class="fas fa-map-marker-alt me-2"></i>Position GPS</h6>
                                <p><strong>Latitude:</strong> ${p.latitude.toFixed(6)}</p>
                                <p><strong>Longitude:</strong> ${p.longitude.toFixed(6)}</p>
                                <p><strong>Heure:</strong> ${new Date(p.recorded_at).toLocaleString('fr-FR')}</p>
                            </div>
                        `);
                        return marker;
                    });

                    const group = L.featureGroup(markers).addTo(map);
                    if (points.length > 1) {
                        map.fitBounds(group.getBounds(), {
                            padding: [20, 20]
                        });
                    }
                } else {
                    // Aucune position, afficher un message
                    const noDataIcon = L.divIcon({
                        className: 'no-data-marker-2050',
                        html: '<div class="no-data-2050"><i class="fas fa-exclamation-triangle"></i><br>Aucune position</div>',
                        iconSize: [200, 100],
                        iconAnchor: [100, 50]
                    });
                    L.marker([46.2276, 2.2137], {
                        icon: noDataIcon
                    }).addTo(map);
                }
            }
        </script>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    </div>
</div>
