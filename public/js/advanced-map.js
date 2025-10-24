/**
 * Carte Interactive Avancée - CheckVan 2050
 * Fonctionnalités : Plein écran, Mode sombre, Actions rapides, Clustering, Traînée GPS, Heatmap
 */

class AdvancedMapService {
    constructor() {
        this.map = null;
        this.markersLayer = null;
        this.clusterGroup = null;
        this.tileLayer = null;
        this.isDarkMode = false;
        this.isFullscreen = false;
        this.chauffeurMarkers = {};
        this.useCluster = false;
        this.clusterThreshold = 20; // Activer clustering si > 20 marqueurs

        // Tiles
        this.tiles = {
            light: {
                url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            },
            dark: {
                url: 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
            }
        };
    }

    /**
     * Initialiser la carte une seule fois
     */
    init(initialLocations) {
        if (this.map) return;
        if (!window.L) return setTimeout(() => this.init(initialLocations), 100);

        const mapEl = document.getElementById('map');
        if (!mapEl) return;

        // Créer la carte
        this.map = L.map(mapEl, {
            preferCanvas: true,
            zoomControl: true,
            scrollWheelZoom: true,
            doubleClickZoom: true,
            boxZoom: true,
            keyboard: true,
            dragging: true,
            touchZoom: true,
            fullscreenControl: false
        }).setView([46.2276, 2.2137], 6);

        // Ajouter les tuiles
        this.updateTiles();

        // Couche pour les marqueurs
        this.markersLayer = L.layerGroup().addTo(this.map);

        // Premier rendu
        this.updateMarkers(initialLocations);

        // Fix le layout
        setTimeout(() => this.map.invalidateSize(), 0);

        // Charger le mode sombre sauvegardé
        const savedDarkMode = localStorage.getItem('checkvan_dark_mode');
        if (savedDarkMode === 'true') {
            this.toggleDarkMode();
        }
    }

    /**
     * Mettre à jour les tuiles (light/dark)
     */
    updateTiles() {
        const tileConfig = this.isDarkMode ? this.tiles.dark : this.tiles.light;

        if (this.tileLayer) {
            this.map.removeLayer(this.tileLayer);
        }

        this.tileLayer = L.tileLayer(tileConfig.url, {
            attribution: tileConfig.attribution,
            maxZoom: 19
        }).addTo(this.map);
    }

    /**
     * Toggle mode sombre
     */
    toggleDarkMode() {
        this.isDarkMode = !this.isDarkMode;
        this.updateTiles();

        // Sauvegarder la préférence
        localStorage.setItem('checkvan_dark_mode', this.isDarkMode);

        // Mettre à jour l'icône du bouton
        const btn = document.getElementById('darkModeBtn');
        if (btn) {
            const icon = btn.querySelector('i');
            if (icon) {
                icon.className = this.isDarkMode ? 'fas fa-sun' : 'fas fa-moon';
            }
        }

        console.log(`Mode ${this.isDarkMode ? 'sombre' : 'clair'} activé`);
    }

    /**
     * Toggle plein écran
     */
    toggleFullscreen() {
        const mapEl = document.getElementById('map');
        if (!mapEl) return;

        this.isFullscreen = !this.isFullscreen;

        if (this.isFullscreen) {
            // Passer en plein écran
            mapEl.classList.add('fullscreen-map');
            mapEl.setAttribute('data-fullscreen', 'true');

            // Changer l'icône
            const btn = document.getElementById('fullscreenBtn');
            if (btn) {
                const icon = btn.querySelector('i');
                if (icon) icon.className = 'fas fa-compress';
            }
        } else {
            // Sortir du plein écran
            mapEl.classList.remove('fullscreen-map');
            mapEl.setAttribute('data-fullscreen', 'false');

            // Changer l'icône
            const btn = document.getElementById('fullscreenBtn');
            if (btn) {
                const icon = btn.querySelector('i');
                if (icon) icon.className = 'fas fa-expand';
            }
        }

        // Recalculer la taille de la carte
        setTimeout(() => this.map.invalidateSize(), 100);

        console.log(`Plein écran ${this.isFullscreen ? 'activé' : 'désactivé'}`);
    }

    /**
     * Mettre à jour les marqueurs avec clustering si nécessaire
     */
    updateMarkers(points) {
        if (!this.map) return;

        // Nettoyer les anciennes couches
        if (this.markersLayer) {
            this.map.removeLayer(this.markersLayer);
            this.markersLayer = null;
        }
        if (this.clusterGroup) {
            this.map.removeLayer(this.clusterGroup);
            this.clusterGroup = null;
        }
        Object.keys(this.chauffeurMarkers).forEach(key => delete this.chauffeurMarkers[key]);

        if (!points || !points.length) {
            this.markersLayer = L.layerGroup().addTo(this.map);
            this.showNoDataMessage();
            return;
        }

        // Décider si on utilise le clustering
        this.useCluster = points.length > this.clusterThreshold;

        if (this.useCluster && window.L.markerClusterGroup) {
            // Utiliser le clustering
            this.clusterGroup = L.markerClusterGroup({
                showCoverageOnHover: false,
                zoomToBoundsOnClick: true,
                spiderfyOnMaxZoom: true,
                removeOutsideVisibleBounds: true,
                iconCreateFunction: function(cluster) {
                    const count = cluster.getChildCount();
                    let className = 'marker-cluster-small';
                    if (count > 10) className = 'marker-cluster-medium';
                    if (count > 50) className = 'marker-cluster-large';

                    return L.divIcon({
                        html: `<div><span>${count}</span></div>`,
                        className: 'marker-cluster ' + className,
                        iconSize: L.point(40, 40)
                    });
                }
            });

            points.forEach(p => {
                const marker = this.createMarker(p);
                this.clusterGroup.addLayer(marker);
            });

            this.map.addLayer(this.clusterGroup);

            console.log(`✅ Clustering activé (${points.length} marqueurs)`);
        } else {
            // Pas de clustering
            this.markersLayer = L.layerGroup().addTo(this.map);
            const markers = points.map(p => this.createMarker(p));

            console.log(`✅ ${points.length} marqueurs affichés (sans clustering)`);
        }

        // Ajuster la vue
        if (points.length > 0) {
            const bounds = points.map(p => [p.latitude, p.longitude]);
            if (points.length > 1) {
                this.map.fitBounds(bounds, { padding: [20, 20] });
            } else {
                this.map.setView([points[0].latitude, points[0].longitude], 13);
            }
        }

        setTimeout(() => this.map.invalidateSize(), 0);
    }

    /**
     * Créer un marqueur pour un chauffeur
     */
    createMarker(p) {
        let color = '#28a745';

        if (p.status === 'hors_ligne') {
            color = '#6c757d';
        } else if (p.status === 'en_attente') {
            color = '#ffc107';
        } else if (p.status === 'disponible') {
            color = '#007bff';
        }

        const chauffeurNom = p.chauffeur_nom || 'Chauffeur';
        const initiales = chauffeurNom.split(' ').map(n => n.charAt(0)).join('').substring(0, 2).toUpperCase();

        const customIcon = L.divIcon({
            className: 'custom-marker-2050',
            html: `<div class="marker-pulse-2050 ${p.is_stale ? 'stale' : ''}" style="background-color:${color};border-color:${color};">
                    <span style="color:#fff;font-weight:bold;font-size:12px;">${initiales}</span>
                   </div>`,
            iconSize: [35, 35],
            iconAnchor: [17, 17]
        });

        const marker = L.marker([p.latitude, p.longitude], { icon: customIcon });

        // Créer le popup avec actions rapides
        const popupContent = this.createPopupContent(p);
        marker.bindPopup(popupContent, { maxWidth: 300 });

        // Ajouter à la couche si elle existe (pas de clustering)
        if (this.markersLayer) {
            marker.addTo(this.markersLayer);
        }

        this.chauffeurMarkers[p.chauffeur_id] = marker;

        return marker;
    }

    /**
     * Créer le contenu HTML du popup avec actions rapides
     */
    createPopupContent(p) {
        const statusBadge = p.status === 'en_cours' ? '<span class="badge bg-success">En cours</span>' :
            p.status === 'disponible' ? '<span class="badge bg-info">Disponible</span>' :
            p.status === 'hors_ligne' ? '<span class="badge bg-secondary">Hors ligne</span>' :
            '<span class="badge bg-warning">En attente</span>';

        const recordedAt = new Date(p.recorded_at);
        const timeAgo = this.getTimeAgo(recordedAt);

        // Actions rapides
        const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${p.latitude},${p.longitude}`;
        const wazeUrl = `https://www.waze.com/ul?ll=${p.latitude},${p.longitude}&navigate=yes`;

        return `
            <div class="popup-2050">
                <h6><i class="fas fa-user me-2"></i>${p.chauffeur_nom}</h6>
                <p><strong>Véhicule:</strong> ${p.vehicule || 'N/A'}</p>
                <p><strong>Statut:</strong> ${statusBadge}</p>
                <p><strong>Position:</strong> ${Number(p.latitude).toFixed(6)}, ${Number(p.longitude).toFixed(6)}</p>
                <p><strong>Dernière MAJ:</strong> ${timeAgo}</p>

                <div class="popup-actions mt-3">
                    <div class="btn-group w-100" role="group">
                        <a href="${mapsUrl}" target="_blank" class="btn btn-sm btn-outline-primary" title="Itinéraire Google Maps">
                            <i class="fas fa-route"></i>
                        </a>
                        <a href="${wazeUrl}" target="_blank" class="btn btn-sm btn-outline-success" title="Ouvrir dans Waze">
                            <i class="fab fa-waze"></i>
                        </a>
                        <button onclick="focusOnChauffeur(${p.latitude}, ${p.longitude}, '${p.chauffeur_nom}')"
                                class="btn btn-sm btn-outline-info" title="Centrer sur la carte">
                            <i class="fas fa-crosshairs"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Afficher message "Aucune donnée"
     */
    showNoDataMessage() {
        const noDataIcon = L.divIcon({
            className: 'no-data-marker-2050',
            html: '<div class="no-data-2050"><i class="fas fa-exclamation-triangle"></i><br>Aucune position</div>',
            iconSize: [200, 100],
            iconAnchor: [100, 50]
        });
        L.marker([46.2276, 2.2137], { icon: noDataIcon }).addTo(this.markersLayer);
    }

    /**
     * Focus sur un chauffeur
     */
    focusOnChauffeur(lat, lng, name) {
        if (!this.map) return;

        this.map.setView([lat, lng], 15, { animation: true });

        // Ouvrir le popup
        this.markersLayer.eachLayer(function(layer) {
            if (layer instanceof L.Marker) {
                const pos = layer.getLatLng();
                if (Math.abs(pos.lat - lat) < 0.0001 && Math.abs(pos.lng - lng) < 0.0001) {
                    layer.openPopup();
                }
            }
        });
    }

    /**
     * Calculer "il y a X minutes"
     */
    getTimeAgo(date) {
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);

        if (diffMins < 1) return 'À l\'instant';
        if (diffMins < 60) return `Il y a ${diffMins} min`;

        const diffHours = Math.floor(diffMins / 60);
        if (diffHours < 24) return `Il y a ${diffHours}h`;

        return date.toLocaleString('fr-FR');
    }
}

// Instance globale
window.advancedMapService = new AdvancedMapService();

// Fonctions globales pour les boutons
window.toggleDarkMode = function() {
    window.advancedMapService.toggleDarkMode();
};

window.toggleFullscreen = function() {
    window.advancedMapService.toggleFullscreen();
};

window.focusOnChauffeur = function(lat, lng, name) {
    window.advancedMapService.focusOnChauffeur(lat, lng, name);
};

