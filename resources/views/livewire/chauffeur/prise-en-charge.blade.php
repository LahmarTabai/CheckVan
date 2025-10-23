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
                <i class="fas fa-hand-paper me-3"></i>Prise en Charge
            </h2>
            <p class="text-muted mb-0">Gérez vos véhicules et signalez les dommages</p>
        </div>
    </div>

    @if ($affectation)
        {{-- Véhicule actuellement pris en charge --}}
        <div class="card-2050 mb-4">
            <div class="card-header-2050">
                <h6 class="mb-0">
                    <i class="fas fa-car me-2"></i>Véhicule actuellement pris en charge
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>{{ $affectation->vehicule->immatriculation }}</h5>
                        <p class="text-muted">
                            {{ $affectation->vehicule->marque->nom ?? 'N/A' }}
                            {{ $affectation->vehicule->modele->nom ?? 'N/A' }}
                        </p>
                        <p><strong>Pris en charge le :</strong>
                            {{ $affectation->date_debut ? \Carbon\Carbon::parse($affectation->date_debut)->format('d/m/Y') : 'Non défini' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <button wire:click="showDommageModal" class="btn btn-warning-2050">
                                <i class="fas fa-exclamation-triangle me-2"></i>Signaler un dommage
                            </button>
                            <button wire:click="rendreVehicule" class="btn btn-danger-2050">
                                <i class="fas fa-undo me-2"></i>Restituer le véhicule
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Restitution --}}
        <div class="card-2050">
            <div class="card-header-2050">
                <h6 class="mb-0">
                    <i class="fas fa-undo me-2"></i>Restitution du véhicule
                </h6>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="rendreVehicule">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Photos des faces (2-6 photos) <span
                                        class="text-danger">*</span></label>
                                <input type="file" wire:model="photos_fin" multiple accept="image/*"
                                    class="form-control-2050">
                                @error('photos_fin')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Photo du compteur <span
                                        class="text-danger">*</span></label>
                                <input type="file" wire:model="photo_compteur_fin" accept="image/*"
                                    class="form-control-2050">
                                @error('photo_compteur_fin')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Photo du carburant <span
                                        class="text-danger">*</span></label>
                                <input type="file" wire:model="photo_carburant_fin" accept="image/*"
                                    class="form-control-2050">
                                @error('photo_carburant_fin')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Kilométrage final <span
                                        class="text-danger">*</span></label>
                                <input type="number" wire:model="kilometrage_fin" class="form-control-2050"
                                    placeholder="Kilométrage">
                                @error('kilometrage_fin')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Niveau de carburant final (%) <span
                                        class="text-danger">*</span></label>
                                <input type="number" wire:model="niveau_carburant_fin" class="form-control-2050"
                                    min="0" max="100" step="0.1" placeholder="%">
                                @error('niveau_carburant_fin')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Géolocalisation <span
                                        class="text-danger">*</span></label>
                                <div class="d-flex gap-2">
                                    <input type="number" wire:model="latitude_fin" class="form-control-2050"
                                        step="any" placeholder="Latitude" readonly>
                                    <input type="number" wire:model="longitude_fin" class="form-control-2050"
                                        step="any" placeholder="Longitude" readonly>
                                    <button type="button" onclick="getCurrentLocationFin()"
                                        class="btn btn-primary-2050">
                                        <i class="fas fa-location-arrow me-2"></i>Obtenir ma position
                                    </button>
                                </div>
                                <small class="text-muted">Cliquez sur "Obtenir ma position" pour capturer
                                    automatiquement votre localisation</small>
                                @error('latitude_fin')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @error('longitude_fin')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-danger-2050">
                            <i class="fas fa-undo me-2"></i>Restituer le véhicule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        {{-- Formulaire de prise en charge --}}
        <div class="card-2050">
            <div class="card-header-2050">
                <h6 class="mb-0">
                    <i class="fas fa-car me-2"></i>Prendre un véhicule en charge
                </h6>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="prendreEnCharge">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Véhicule disponible <span
                                        class="text-danger">*</span></label>
                                <select wire:model="vehicule_id" class="form-control-2050">
                                    <option value="">Sélectionner un véhicule</option>
                                    @foreach ($vehicules as $vehicule)
                                        <option value="{{ $vehicule->id }}">
                                            {{ $vehicule->immatriculation }} -
                                            {{ $vehicule->marque->nom ?? 'N/A' }}
                                            {{ $vehicule->modele->nom ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vehicule_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Plaque d'immatriculation <span
                                        class="text-danger">*</span></label>
                                <input type="text" wire:model="plaque_immatriculation" class="form-control-2050"
                                    placeholder="Ex: AB-123-CD">
                                @error('plaque_immatriculation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Photos des faces (2-6 photos) <span
                                        class="text-danger">*</span></label>
                                <input type="file" wire:model="photos_faces" multiple accept="image/*"
                                    class="form-control-2050">
                                <small class="text-muted">Prenez des photos de toutes les faces du véhicule</small>
                                @error('photos_faces')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Photo du compteur <span
                                        class="text-danger">*</span></label>
                                <input type="file" wire:model="photo_compteur" accept="image/*"
                                    class="form-control-2050">
                                @error('photo_compteur')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Photo du carburant <span
                                        class="text-danger">*</span></label>
                                <input type="file" wire:model="photo_carburant" accept="image/*"
                                    class="form-control-2050">
                                @error('photo_carburant')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Kilométrage <span class="text-danger">*</span></label>
                                <input type="number" wire:model="kilometrage" class="form-control-2050"
                                    placeholder="Kilométrage actuel">
                                @error('kilometrage')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Niveau de carburant (%) <span
                                        class="text-danger">*</span></label>
                                <input type="number" wire:model="niveau_carburant" class="form-control-2050"
                                    min="0" max="100" step="0.1" placeholder="%">
                                @error('niveau_carburant')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Géolocalisation <span
                                        class="text-danger">*</span></label>
                                <div class="d-flex gap-2">
                                    <input type="number" wire:model="latitude" class="form-control-2050"
                                        step="any" placeholder="Latitude" readonly>
                                    <input type="number" wire:model="longitude" class="form-control-2050"
                                        step="any" placeholder="Longitude" readonly>
                                    <button type="button" onclick="getCurrentLocation()"
                                        class="btn btn-primary-2050">
                                        <i class="fas fa-location-arrow me-2"></i>Obtenir ma position
                                    </button>
                                </div>
                                <small class="text-muted">Cliquez sur "Obtenir ma position" pour capturer
                                    automatiquement votre localisation</small>
                                @error('latitude')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @error('longitude')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary-2050">
                            <i class="fas fa-hand-paper me-2"></i>Prendre en charge
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Dommage --}}
    @if ($showDommageModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content card-2050">
                    <div class="modal-header card-header-2050">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>Signaler un dommage
                        </h5>
                        <button type="button" wire:click="closeDommageModal" class="btn-close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form wire:submit.prevent="ajouterDommage">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-2050 mb-3">
                                        <label class="form-label-2050">Type de dommage <span
                                                class="text-danger">*</span></label>
                                        <select wire:model="dommage_type" class="form-control-2050">
                                            <option value="">Sélectionner un type</option>
                                            <option value="rayure">Rayure</option>
                                            <option value="bosse">Bosse</option>
                                            <option value="bris">Bris</option>
                                            <option value="fissure">Fissure</option>
                                            <option value="autre">Autre</option>
                                        </select>
                                        @error('dommage_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-2050 mb-3">
                                        <label class="form-label-2050">Sévérité <span
                                                class="text-danger">*</span></label>
                                        <select wire:model="dommage_severite" class="form-control-2050">
                                            <option value="mineur">Mineur</option>
                                            <option value="moyen">Moyen</option>
                                            <option value="majeur">Majeur</option>
                                        </select>
                                        @error('dommage_severite')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Description <span class="text-danger">*</span></label>
                                <textarea wire:model="dommage_description" class="form-control-2050" rows="3"
                                    placeholder="Décrivez le dommage observé..."></textarea>
                                @error('dommage_description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group-2050 mb-3">
                                <label class="form-label-2050">Photo du dommage <span
                                        class="text-danger">*</span></label>
                                <input type="file" wire:model="dommage_photo" accept="image/*"
                                    class="form-control-2050">
                                @error('dommage_photo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-2050 mb-3">
                                        <label class="form-label-2050">Position X (0-1) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" wire:model="dommage_coord_x" class="form-control-2050"
                                            min="0" max="1" step="0.01" placeholder="0.5">
                                        @error('dommage_coord_x')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-2050 mb-3">
                                        <label class="form-label-2050">Position Y (0-1) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" wire:model="dommage_coord_y" class="form-control-2050"
                                            min="0" max="1" step="0.01" placeholder="0.5">
                                        @error('dommage_coord_y')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-2050 mb-3">
                                        <label class="form-label-2050">Position Z (profondeur)</label>
                                        <input type="number" wire:model="dommage_coord_z" class="form-control-2050"
                                            step="0.01" placeholder="0">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" wire:click="closeDommageModal"
                                    class="btn btn-outline-secondary-2050">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </button>
                                <button type="submit" class="btn btn-warning-2050">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Signaler le dommage
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
    // Géolocalisation pour la prise en charge
    function getCurrentLocation() {
        const button = event.target;
        const originalText = button.innerHTML;

        // Afficher un indicateur de chargement
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Localisation...';
        button.disabled = true;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    @this.set('latitude', position.coords.latitude);
                    @this.set('longitude', position.coords.longitude);

                    // Restaurer le bouton
                    button.innerHTML = originalText;
                    button.disabled = false;

                    // Afficher un message de succès
                    const successAlert = document.createElement('div');
                    successAlert.className = 'alert alert-success-2050 alert-dismissible fade show mt-2';
                    successAlert.innerHTML =
                        '<i class="fas fa-check-circle me-2"></i>Position capturée avec succès !';
                    button.parentNode.parentNode.appendChild(successAlert);

                    // Supprimer l'alerte après 3 secondes
                    setTimeout(() => successAlert.remove(), 3000);
                },
                function(error) {
                    console.error('Erreur de géolocalisation:', error);

                    // Restaurer le bouton
                    button.innerHTML = originalText;
                    button.disabled = false;

                    // Afficher un message d'erreur selon le type d'erreur
                    let errorMessage = 'Impossible d\'obtenir votre position. ';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage +=
                                'Veuillez autoriser l\'accès à votre position dans les paramètres du navigateur.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Votre position n\'est pas disponible.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'La demande de position a expiré.';
                            break;
                        default:
                            errorMessage += 'Erreur inconnue.';
                            break;
                    }

                    alert(errorMessage);
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000 // 5 minutes
                }
            );
        } else {
            alert(
                'La géolocalisation n\'est pas supportée par votre navigateur. Veuillez utiliser un navigateur plus récent.');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    }

    // Géolocalisation pour la restitution
    function getCurrentLocationFin() {
        const button = event.target;
        const originalText = button.innerHTML;

        // Afficher un indicateur de chargement
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Localisation...';
        button.disabled = true;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    @this.set('latitude_fin', position.coords.latitude);
                    @this.set('longitude_fin', position.coords.longitude);

                    // Restaurer le bouton
                    button.innerHTML = originalText;
                    button.disabled = false;

                    // Afficher un message de succès
                    const successAlert = document.createElement('div');
                    successAlert.className = 'alert alert-success-2050 alert-dismissible fade show mt-2';
                    successAlert.innerHTML =
                        '<i class="fas fa-check-circle me-2"></i>Position capturée avec succès !';
                    button.parentNode.parentNode.appendChild(successAlert);

                    // Supprimer l'alerte après 3 secondes
                    setTimeout(() => successAlert.remove(), 3000);
                },
                function(error) {
                    console.error('Erreur de géolocalisation:', error);

                    // Restaurer le bouton
                    button.innerHTML = originalText;
                    button.disabled = false;

                    // Afficher un message d'erreur selon le type d'erreur
                    let errorMessage = 'Impossible d\'obtenir votre position. ';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage +=
                                'Veuillez autoriser l\'accès à votre position dans les paramètres du navigateur.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Votre position n\'est pas disponible.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'La demande de position a expiré.';
                            break;
                        default:
                            errorMessage += 'Erreur inconnue.';
                            break;
                    }

                    alert(errorMessage);
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000 // 5 minutes
                }
            );
        } else {
            alert(
                'La géolocalisation n\'est pas supportée par votre navigateur. Veuillez utiliser un navigateur plus récent.');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    }
</script>
