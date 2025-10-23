<div>
    <!-- En-tête du tableau de bord -->
    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            <div class="glass-effect rounded-circle p-3">
                <i class="fas fa-tachometer-alt text-gradient fs-4"></i>
            </div>
        </div>
        <div>
            <h1 class="text-gradient mb-0">Tableau de Bord</h1>
            <p class="text-muted mb-0">Vue d'ensemble de vos activités 2050</p>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="row g-4 mb-4">
        <!-- Tâches totales -->
        <div class="col-md-3">
            <div class="card-2050 hover-lift text-center">
                <div class="card-body p-4">
                    <div class="glass-effect rounded-circle p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-tasks text-gradient fs-4"></i>
                    </div>
                    <h3 class="text-gradient mb-1">{{ $statistiques['total_taches'] }}</h3>
                    <p class="text-muted mb-0">Tâches totales</p>
                </div>
            </div>
        </div>

        <!-- Tâches terminées -->
        <div class="col-md-3">
            <div class="card-2050 hover-lift text-center">
                <div class="card-body p-4">
                    <div class="glass-effect rounded-circle p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-check-circle text-success fs-4"></i>
                    </div>
                    <h3 class="text-success mb-1">{{ $statistiques['taches_terminees'] }}</h3>
                    <p class="text-muted mb-0">Terminées</p>
                </div>
            </div>
        </div>

        <!-- Tâches en cours -->
        <div class="col-md-3">
            <div class="card-2050 hover-lift text-center">
                <div class="card-body p-4">
                    <div class="glass-effect rounded-circle p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-play-circle text-warning fs-4"></i>
                    </div>
                    <h3 class="text-warning mb-1">{{ $statistiques['taches_en_cours'] }}</h3>
                    <p class="text-muted mb-0">En cours</p>
                </div>
            </div>
        </div>

        <!-- Tâches en attente -->
        <div class="col-md-3">
            <div class="card-2050 hover-lift text-center">
                <div class="card-body p-4">
                    <div class="glass-effect rounded-circle p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-clock text-info fs-4"></i>
                    </div>
                    <h3 class="text-info mb-1">{{ $statistiques['taches_en_attente'] }}</h3>
                    <p class="text-muted mb-0">En attente</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques secondaires -->
    <div class="row g-4 mb-4">
        <!-- Kilométrage total -->
        <div class="col-md-4">
            <div class="card-2050 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="glass-effect rounded-circle p-3 me-3">
                            <i class="fas fa-route text-gradient"></i>
                        </div>
                        <div>
                            <h4 class="text-gradient mb-1">{{ number_format($statistiques['kilometrage_total']) }} km
                            </h4>
                            <p class="text-muted mb-0">Kilométrage total</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Taux de validation -->
        <div class="col-md-4">
            <div class="card-2050 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="glass-effect rounded-circle p-3 me-3">
                            <i class="fas fa-award text-gradient"></i>
                        </div>
                        <div>
                            <h4 class="text-gradient mb-1">{{ $statistiques['taux_validation'] }}%</h4>
                            <p class="text-muted mb-0">Taux de validation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Tâches ce mois -->
    <div class="col-md-4">
        <div class="card-2050 hover-lift">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="glass-effect rounded-circle p-3 me-3">
                        <i class="fas fa-calendar-alt text-gradient"></i>
                    </div>
                    <div>
                        <h4 class="text-gradient mb-1">{{ $statistiques['taches_ce_mois'] }}</h4>
                        <p class="text-muted mb-0">Ce mois</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Véhicule affecté -->
        <div class="col-md-6">
            <div class="card-2050 hover-lift">
                <div class="card-header-2050">
                    <h6 class="mb-0">
                        <i class="fas fa-car me-2"></i>Véhicule Actuel
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if ($vehiculeAffecte)
                        <div class="d-flex align-items-center mb-3">
                            <div class="glass-effect rounded-circle p-3 me-3">
                                <i class="fas fa-car text-gradient"></i>
                            </div>
                            <div>
                                <h5 class="text-gradient mb-1">
                                    {{ $vehiculeAffecte->vehicule->marque->nom ?? 'N/A' }}
                                    {{ $vehiculeAffecte->vehicule->modele->nom ?? 'N/A' }}
                                </h5>
                                <p class="text-muted mb-0">{{ $vehiculeAffecte->vehicule->immatriculation }}</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted">Type</small>
                                <p class="mb-0">{{ ucfirst($vehiculeAffecte->vehicule->type) }}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Année</small>
                                <p class="mb-0">{{ $vehiculeAffecte->vehicule->annee }}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Kilométrage</small>
                                <p class="mb-0">{{ number_format($vehiculeAffecte->vehicule->kilometrage ?? 0) }} km
                                </p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Statut</small>
                                <span class="badge badge-success-2050">
                                    <i class="fas fa-check me-1"></i>Actif
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="glass-effect rounded-circle p-4 mx-auto mb-3"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-car text-muted fs-2"></i>
                            </div>
                            <h5 class="text-muted mb-3">Aucun véhicule assigné</h5>
                            <a href="{{ route('chauffeur.prise-en-charge') }}" class="btn btn-primary-2050">
                                <i class="fas fa-hand-paper me-2"></i>Prendre un véhicule
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tâches récentes -->
        <div class="col-md-6">
            <div class="card-2050 hover-lift">
                <div class="card-header-2050">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>Tâches Récentes
                    </h6>
                </div>
                <div class="card-body p-0">
                    @forelse($tachesRecentes as $tache)
                        <div class="border-bottom p-3 {{ $loop->last ? 'border-0' : '' }}">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="glass-effect rounded-circle p-2 me-3">
                                        @php
                                            $typeIcons = [
                                                'maintenance' => 'wrench',
                                                'livraison' => 'truck',
                                                'inspection' => 'search',
                                                'autre' => 'question',
                                            ];
                                        @endphp
                                        <i
                                            class="fas fa-{{ $typeIcons[$tache->type_tache] ?? 'question' }} text-gradient"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ ucfirst($tache->type_tache) }}</h6>
                                        <small class="text-muted">
                                            {{ $tache->vehicule->marque->nom ?? 'N/A' }}
                                            {{ $tache->vehicule->modele->nom ?? 'N/A' }}
                                        </small>
                                    </div>
                                </div>
                                <div class="text-end">
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
                                    <span
                                        class="badge {{ $statusClasses[$tache->status] ?? 'badge-secondary-2050' }}">
                                        <i class="fas fa-{{ $statusIcons[$tache->status] ?? 'question' }} me-1"></i>
                                        {{ ucfirst($tache->status) }}
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        {{ $tache->start_date ? $tache->start_date->format('d/m H:i') : 'N/A' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <div class="glass-effect rounded-circle p-4 mx-auto mb-3"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-inbox text-muted fs-2"></i>
                            </div>
                            <h5 class="text-muted mb-3">Aucune tâche</h5>
                            <p class="text-muted">Vous n'avez pas encore de tâches assignées.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row g-4 mt-4">
            <div class="col-12">
                <div class="card-2050 hover-lift">
                    <div class="card-header-2050">
                        <h6 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>Actions Rapides
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="{{ route('chauffeur.taches') }}" class="btn btn-primary-2050 w-100">
                                    <i class="fas fa-tasks me-2"></i>Mes Tâches
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('chauffeur.prise-en-charge') }}" class="btn btn-info-2050 w-100">
                                    <i class="fas fa-hand-paper me-2"></i>Prise en Charge
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('chauffeur.dommages', 0) }}" class="btn btn-warning-2050 w-100">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Signaler Dommage
                                </a>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success-2050 w-100" onclick="getCurrentLocation()">
                                    <i class="fas fa-map-marker-alt me-2"></i>Ma Position
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                let gpsInterval = null;
                let isTracking = false;

                function getCurrentLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            // Afficher la position dans une alerte
                            alert(`Votre position actuelle :\nLatitude: ${lat.toFixed(6)}\nLongitude: ${lng.toFixed(6)}`);

                            // Optionnel : ouvrir Google Maps
                            const mapsUrl = `https://www.google.com/maps?q=${lat},${lng}`;
                            if (confirm('Voulez-vous ouvrir cette position dans Google Maps ?')) {
                                window.open(mapsUrl, '_blank');
                            }
                        }, function(error) {
                            alert('Erreur de géolocalisation : ' + error.message);
                        });
                    } else {
                        alert('La géolocalisation n\'est pas supportée par ce navigateur.');
                    }
                }

                function startGPSTracking() {
                    if (isTracking) return;

                    isTracking = true;
                    console.log('GPS Tracking démarré');

                    // Envoyer la position immédiatement
                    sendGPSPosition();

                    // Puis toutes les 30 secondes
                    gpsInterval = setInterval(sendGPSPosition, 30000);
                }

                function stopGPSTracking() {
                    if (!isTracking) return;

                    isTracking = false;
                    console.log('GPS Tracking arrêté');

                    if (gpsInterval) {
                        clearInterval(gpsInterval);
                        gpsInterval = null;
                    }
                }

                function sendGPSPosition() {
                    if (!navigator.geolocation) return;

                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            // Envoyer au serveur via API
                            const csrfToken = document.querySelector('meta[name="csrf-token"]');
                            const headers = {
                                'Content-Type': 'application/json'
                            };

                            if (csrfToken) {
                                headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
                            }

                            fetch('/api/location', {
                                    method: 'POST',
                                    headers: headers,
                                    credentials: 'same-origin',
                                    body: JSON.stringify({
                                        latitude: lat,
                                        longitude: lng,
                                        recorded_at: new Date().toISOString()
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    console.log('Position GPS envoyée avec succès:', data);
                                })
                                .catch(error => {
                                    console.error('Erreur envoi GPS:', error);
                                });
                        },
                        function(error) {
                            console.error('Erreur géolocalisation:', error);
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 30000
                        }
                    );
                }

                // GPS automatique - démarrer immédiatement si tâche en cours
                document.addEventListener('DOMContentLoaded', function() {
                    @if (isset($tacheEnCours) && $tacheEnCours)
                        // Démarrer le GPS automatiquement sans interface
                        startGPSTracking();
                    @endif
                });
            </script>
        </div>
