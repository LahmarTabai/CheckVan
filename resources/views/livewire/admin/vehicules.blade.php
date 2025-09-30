<div>
    <div>
        <div class="d-flex align-items-center mb-4">
            <div class="me-3">
                <div class="glass-effect rounded-circle p-3">
                    <i class="fas fa-car text-gradient fs-4"></i>
                </div>
            </div>
            <div>
                <h1 class="text-gradient mb-0">Gestion des Véhicules</h1>
                <p class="text-muted mb-0">Flotte intelligente 2050</p>
            </div>
        </div>


        @if (session()->has('success'))
            <div class="alert alert-success-2050 alert-dismissible fade show animate-fade-in-up" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Bouton Ajouter Véhicule -->
        @if (!$showForm && !$isEdit)
            <div class="d-flex justify-content-end mb-4">
                <button type="button" wire:click="showAddForm" class="btn btn-primary-2050">
                    <i class="fas fa-plus me-2"></i>Ajouter un véhicule
                </button>
            </div>
        @endif

        <!-- Formulaire Futuriste -->
        @if ($showForm || $isEdit)
            <div class="card-2050 mb-4 hover-lift">
                <div class="card-header-2050">
                    <h6 class="mb-0">
                        <i class="fas fa-plus me-2"></i>{{ $isEdit ? 'Modifier le véhicule' : 'Ajouter un véhicule' }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" id="vehicule-form">
                        <div class="form-section-2050">
                            <h6 class="section-title-2050">
                                <i class="fas fa-info-circle me-2"></i>Informations de base
                            </h6>

                            <div class="form-row-2050">
                                <div class="form-col-2050 col-md-6">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">
                                            Immatriculation <span class="required">*</span>
                                        </label>
                                        <input type="text" wire:model="immatriculation" class="form-control-2050"
                                            placeholder="Ex: AB-123-CD">
                                        <small class="form-help-2050">Numéro d'immatriculation du véhicule</small>
                                        @error('immatriculation')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-col-2050 col-md-6">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">
                                            Type de véhicule <span class="required">*</span>
                                        </label>
                                        <div wire:ignore>
                                            <select id="form-type" class="form-control-2050 select2-2050">
                                                <option value="">-- Sélectionner le type --</option>
                                                <option value="propriete" @selected($type === 'propriete')>Propriété
                                                </option>
                                                <option value="location" @selected($type === 'location')>Location</option>
                                            </select>
                                        </div>
                                        <small class="form-help-2050">Propriété de l'entreprise ou véhicule en
                                            location</small>
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section-2050">
                            <h6 class="section-title-2050">
                                <i class="fas fa-car me-2"></i>Caractéristiques du véhicule
                            </h6>

                            <div class="form-row-2050">
                                <div class="form-col-2050 col-md-4">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">
                                            Marque <span class="required">*</span>
                                        </label>
                                        <div wire:ignore>
                                            <select id="form-marque" class="form-control-2050 select2-2050">
                                                <option value="">-- Sélectionner une marque --</option>
                                                @foreach ($marques as $marque)
                                                    <option value="{{ $marque->id }}">{{ $marque->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('marque_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-col-2050 col-md-4">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">
                                            Modèle <span class="required">*</span>
                                        </label>

                                        <div wire:loading wire:target="marque_id" class="mb-2">
                                            <span class="spinner-border spinner-border-sm text-primary"></span>
                                            <small class="text-muted">Chargement des modèles...</small>
                                        </div>

                                        <div wire:ignore>
                                            <select id="form-modele" class="form-control-2050 select2-2050">
                                                <option value="">-- Sélectionner un modèle --</option>
                                                @foreach ($formModeles as $modele)
                                                    <option value="{{ $modele->id }}">{{ $modele->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('modele_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-col-2050 col-md-4">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">
                                            Couleur <span class="required">*</span>
                                        </label>
                                        <div wire:ignore>
                                            <select id="form-couleur" class="form-control-2050 select2-2050">
                                                <option value="">-- Sélectionner une couleur --</option>
                                                @foreach ($couleurs as $couleur)
                                                    <option value="{{ $couleur }}">{{ $couleur }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('couleur')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-row-2050">
                                <div class="form-col-2050 col-md-3">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">
                                            Année <span class="required">*</span>
                                        </label>
                                        <input type="number" wire:model="annee" class="form-control-2050"
                                            min="1990" max="{{ date('Y') + 1 }}" placeholder="2024">
                                        <small class="form-help-2050">Année de fabrication du véhicule</small>
                                        @error('annee')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-col-2050 col-md-3">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">Kilométrage</label>
                                        <input type="number" wire:model="kilometrage" class="form-control-2050"
                                            placeholder="0">
                                        <small class="form-help-2050">Kilométrage actuel du véhicule</small>
                                        @error('kilometrage')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-col-2050 col-md-3">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">
                                            Statut <span class="required">*</span>
                                        </label>
                                        <div wire:ignore>
                                            <select id="form-statut" class="form-control-2050 select2-2050">
                                                <option value="">-- Sélectionner le statut --</option>
                                                <option value="disponible">Disponible</option>
                                                <option value="en_mission">En mission</option>
                                                <option value="en_maintenance">En maintenance</option>
                                                <option value="hors_service">Hors service</option>
                                            </select>
                                        </div>
                                        @error('statut')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section-2050">
                            <h6 class="section-title-2050">
                                <i class="fas fa-cogs me-2"></i>Informations techniques
                            </h6>

                            <div class="form-row-2050">
                                <div class="form-col-2050 col-md-3">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">Prochaine révision</label>
                                        <input type="date" wire:model="prochaine_revision"
                                            class="form-control-2050">
                                        <small class="form-help-2050">Date de la prochaine révision prévue</small>
                                        @error('prochaine_revision')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-col-2050 col-md-3">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">Dernière révision</label>
                                        <input type="date" wire:model="derniere_revision"
                                            class="form-control-2050">
                                        <small class="form-help-2050">Date de la dernière révision effectuée</small>
                                        @error('derniere_revision')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-col-2050 col-md-6">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">Numéro de châssis</label>
                                        <input type="text" wire:model="numero_chassis" class="form-control-2050"
                                            placeholder="Ex: VF1234567890123456">
                                        <small class="form-help-2050">Numéro d'identification du châssis (VIN)</small>
                                        @error('numero_chassis')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-row-2050">
                                <div class="form-col-2050 col-md-6">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">Numéro de moteur</label>
                                        <input type="text" wire:model="numero_moteur" class="form-control-2050"
                                            placeholder="Ex: MOTEUR123456">
                                        <small class="form-help-2050">Numéro d'identification du moteur</small>
                                        @error('numero_moteur')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-col-2050 col-md-6">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">Description</label>
                                        <textarea wire:model="description" class="form-control-2050" rows="3"
                                            placeholder="Description du véhicule, équipements, état général..."></textarea>
                                        <small class="form-help-2050">Informations complémentaires sur le
                                            véhicule</small>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>


                        <script>
                            // Fonction pour synchroniser les valeurs Select2 avant soumission
                            function syncSelect2Values() {
                                console.log('=== SYNC SELECT2 VALUES ===');

                                try {
                                    const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                                    if (livewireComponent) {
                                        // Synchroniser le type
                                        const typeValue = $('#select-type').val();
                                        console.log('Type Select2:', typeValue);
                                        if (typeValue) {
                                            livewireComponent.set('type', typeValue);
                                            console.log('Type synchronisé vers Livewire');
                                        }

                                        // Synchroniser la marque
                                        const marqueValue = $('select[wire\\:model\\.live="marque_id"]').val();
                                        console.log('Marque Select2:', marqueValue);
                                        if (marqueValue) {
                                            livewireComponent.set('marque_id', marqueValue);
                                            console.log('Marque synchronisée vers Livewire');
                                        }

                                        // Synchroniser le modèle
                                        const modeleValue = $('select[wire\\:model\\.live="modele_id"]').val();
                                        console.log('Modèle Select2:', modeleValue);
                                        if (modeleValue) {
                                            // Utiliser set directement
                                            livewireComponent.set('modele_id', modeleValue);
                                            console.log('Modèle synchronisé vers Livewire via set');
                                        }

                                        // Synchroniser la couleur
                                        const couleurValue = $('select[wire\\:model="couleur"]').val();
                                        console.log('Couleur Select2:', couleurValue);
                                        if (couleurValue) {
                                            livewireComponent.set('couleur', couleurValue);
                                            console.log('Couleur synchronisée vers Livewire');
                                        }

                                        // Synchroniser le statut
                                        const statutValue = $('select[wire\\:model="statut"]').val();
                                        console.log('Statut Select2:', statutValue);
                                        if (statutValue) {
                                            livewireComponent.set('statut', statutValue);
                                            console.log('Statut synchronisé vers Livewire');
                                        }

                                        console.log('=== SYNC TERMINÉ ===');

                                        // Attendre un peu pour que Livewire traite les changements
                                        setTimeout(function() {
                                            console.log('Synchronisation terminée, soumission du formulaire...');
                                        }, 100);

                                        return true;
                                    } else {
                                        console.error('Composant Livewire non trouvé');
                                        return false;
                                    }
                                } catch (error) {
                                    console.error('Erreur lors de la synchronisation:', error);
                                    return false;
                                }
                            }

                            // Fonction pour initialiser les événements Livewire
                            function initLivewireEvents() {
                                if (typeof Livewire !== 'undefined') {
                                    console.log('Livewire disponible, initialisation des événements...');

                                    // Synchronisation manuelle Select2 → Livewire
                                    $('#select-type').off('change').on('change', function() {
                                        const selectedValue = $(this).val();
                                        console.log('Type changé vers:', selectedValue);

                                        // Trouver le composant Livewire et mettre à jour la propriété
                                        const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                                            'wire:id'));
                                        if (livewireComponent) {
                                            livewireComponent.set('type', selectedValue);
                                            console.log('Type synchronisé vers Livewire');
                                        }
                                    });

                                    // Synchronisation Livewire → Select2 (pour les mises à jour côté serveur)
                                    Livewire.hook('message.processed', (message, component) => {
                                        if (message.updateQueue.some(update => update.payload.name === 'type')) {
                                            console.log('Type mis à jour côté serveur');
                                            // Mettre à jour Select2 si nécessaire
                                            const currentValue = $('#select-type').val();
                                            const livewireValue = component.get('type');
                                            if (currentValue !== livewireValue) {
                                                $('#select-type').val(livewireValue).trigger('change');
                                            }
                                        }
                                    });

                                    // Écouter l'événement de synchronisation des Select2
                                    Livewire.on('sync-select2-values', () => {
                                        console.log('Synchronisation des Select2 pour l\'édition...');
                                        setTimeout(function() {
                                            // Récupérer le composant Livewire
                                            const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]')
                                                .getAttribute('wire:id'));
                                            if (!livewireComponent) {
                                                console.error('Composant Livewire non trouvé pour la synchronisation');
                                                return;
                                            }

                                            // Synchroniser le type
                                            const typeValue = livewireComponent.get('type');
                                            console.log('Type Livewire:', typeValue);
                                            if (typeValue) {
                                                $('#select-type').val(typeValue).trigger('change');
                                                console.log('Type Select2 mis à jour vers:', typeValue);
                                            }

                                            // Synchroniser la marque
                                            const marqueValue = livewireComponent.get('marque_id');
                                            console.log('Marque Livewire:', marqueValue);
                                            if (marqueValue) {
                                                $('select[wire\\:model\\.live="marque_id"]').val(marqueValue).trigger('change');
                                                console.log('Marque Select2 mise à jour vers:', marqueValue);
                                            }

                                            // Synchroniser le modèle
                                            const modeleValue = livewireComponent.get('modele_id');
                                            console.log('Modèle Livewire:', modeleValue);
                                            if (modeleValue) {
                                                $('select[wire\\:model\\.live="modele_id"]').val(modeleValue).trigger('change');
                                                console.log('Modèle Select2 mis à jour vers:', modeleValue);
                                            }

                                            // Synchroniser la couleur
                                            const couleurValue = livewireComponent.get('couleur');
                                            console.log('Couleur Livewire:', couleurValue);
                                            if (couleurValue) {
                                                $('select[wire\\:model="couleur"]').val(couleurValue).trigger('change');
                                                console.log('Couleur Select2 mise à jour vers:', couleurValue);
                                            }

                                            // Synchroniser le statut
                                            const statutValue = livewireComponent.get('statut');
                                            console.log('Statut Livewire:', statutValue);
                                            if (statutValue) {
                                                $('select[wire\\:model="statut"]').val(statutValue).trigger('change');
                                                console.log('Statut Select2 mis à jour vers:', statutValue);
                                            }

                                            console.log('Synchronisation des Select2 terminée');
                                        }, 200);
                                    });

                                    return true;
                                }
                                return false;
                            }

                            // Attendre que Livewire soit complètement chargé
                            document.addEventListener('DOMContentLoaded', function() {
                                // Essayer d'initialiser immédiatement
                                if (!initLivewireEvents()) {
                                    // Si Livewire n'est pas encore disponible, réessayer
                                    let attempts = 0;
                                    const maxAttempts = 10;

                                    const retryInit = setInterval(function() {
                                        attempts++;
                                        console.log(`Tentative ${attempts}/${maxAttempts} d'initialisation Livewire...`);

                                        if (initLivewireEvents() || attempts >= maxAttempts) {
                                            clearInterval(retryInit);
                                            if (attempts >= maxAttempts) {
                                                console.error('Impossible d\'initialiser Livewire après', maxAttempts,
                                                    'tentatives');
                                            }
                                        }
                                    }, 500);
                                }

                                // Intercepter la soumission du formulaire
                                $('#vehicule-form').on('submit', function(e) {
                                    e.preventDefault();
                                    console.log('=== INTERCEPTION SOUMISSION ===');

                                    // Synchroniser les valeurs Select2
                                    if (syncSelect2Values()) {
                                        // Attendre un peu pour que Livewire traite les changements
                                        setTimeout(function() {
                                            console.log('Synchronisation terminée, soumission du formulaire...');
                                            // Déclencher la soumission Livewire
                                            const livewireComponent = Livewire.find(document.querySelector(
                                                '[wire\\:id]').getAttribute('wire:id'));
                                            if (livewireComponent) {
                                                if (livewireComponent.get('isEdit')) {
                                                    livewireComponent.call('update');
                                                } else {
                                                    livewireComponent.call('store');
                                                }
                                            }
                                        }, 500);
                                    }
                                });
                            });
                        </script>

                        <div wire:key="type-section-{{ $type }}">
                            @if ($type === 'propriete')
                                <div class="form-section-2050">
                                    <h6 class="section-title-2050">
                                        <i class="fas fa-euro-sign me-2"></i>Informations d'achat
                                    </h6>

                                    <div class="form-row-2050">
                                        <div class="form-col-2050 col-md-6">
                                            <div class="form-group-2050">
                                                <label class="form-label-2050">Prix d'achat (€)</label>
                                                <input type="number" wire:model="prix_achat"
                                                    class="form-control-2050" step="0.01" placeholder="0.00">
                                                <small class="form-help-2050">Prix d'achat du véhicule en euros</small>
                                                @error('prix_achat')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-col-2050 col-md-6">
                                            <div class="form-group-2050">
                                                <label class="form-label-2050">Date d'achat</label>
                                                <input type="date" wire:model="date_achat"
                                                    class="form-control-2050">
                                                <small class="form-help-2050">Date d'acquisition du véhicule</small>
                                                @error('date_achat')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($type === 'location')
                                <div class="form-section-2050">
                                    <h6 class="section-title-2050">
                                        <i class="fas fa-handshake me-2"></i>Informations de location
                                    </h6>

                                    <div class="form-row-2050">
                                        <div class="form-col-2050 col-md-6">
                                            <div class="form-group-2050">
                                                <label class="form-label-2050">Prix de location/jour (€)</label>
                                                <input type="number" wire:model="prix_location_jour"
                                                    class="form-control-2050" step="0.01" placeholder="0.00">
                                                <small class="form-help-2050">Coût de location par jour en
                                                    euros</small>
                                                @error('prix_location_jour')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-col-2050 col-md-6">
                                            <div class="form-group-2050">
                                                <label class="form-label-2050">Date de location</label>
                                                <input type="date" wire:model="date_location"
                                                    class="form-control-2050">
                                                <small class="form-help-2050">Date de début de la location</small>
                                                @error('date_location')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="form-section-2050">
                            <h6 class="section-title-2050">
                                <i class="fas fa-images me-2"></i>Photos du véhicule
                            </h6>

                            <div class="form-row-2050">
                                <div class="form-col-2050 col-12">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">Photos du véhicule</label>
                                        <input type="file" wire:model="photos" class="form-control-2050" multiple
                                            accept="image/*">
                                        <small class="form-help-2050">Formats acceptés : JPG, PNG, GIF. Taille max :
                                            8MB
                                            par photo.</small>
                                        @error('photos.*')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Affichage des photos existantes -->
                                    @if ($isEdit && $selectedVehicule && $selectedVehicule->photos->count() > 0)
                                        <div class="mt-3">
                                            <h6 class="text-gradient mb-3">Photos existantes</h6>
                                            <div class="row">
                                                @foreach ($selectedVehicule->photos as $photo)
                                                    <div class="col-md-3 mb-3">
                                                        <div class="position-relative">
                                                            <img src="{{ Storage::url($photo->chemin) }}"
                                                                class="img-fluid rounded"
                                                                style="width: 100%; height: 120px; object-fit: cover;"
                                                                alt="Photo du véhicule">
                                                            <button type="button"
                                                                wire:click="deletePhoto({{ $photo->id }})"
                                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                                                title="Supprimer cette photo">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Affichage des nouvelles photos -->
                                    @if ($photos)
                                        <div class="mt-3">
                                            <h6 class="text-gradient mb-3">Nouvelles photos</h6>
                                            <div class="row">
                                                @foreach ($photos as $index => $photo)
                                                    <div class="col-md-3 mb-3">
                                                        <div class="position-relative">
                                                            <img src="{{ $photo->temporaryUrl() }}"
                                                                class="img-fluid rounded"
                                                                style="width: 100%; height: 120px; object-fit: cover;"
                                                                alt="Nouvelle photo">
                                                            <button type="button"
                                                                wire:click="removePhoto({{ $index }})"
                                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                                                title="Supprimer cette photo">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-actions-2050">
                            <button type="submit" class="btn btn-primary-2050">
                                <i class="fas fa-save me-2"></i>{{ $isEdit ? 'Modifier' : 'Ajouter' }} Véhicule
                            </button>
                            @if ($isEdit)
                                <button type="button" wire:click="resetForm" class="btn btn-outline-2050">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </button>
                            @else
                                <button type="button" wire:click="hideForm" class="btn btn-outline-2050">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </button>
                            @endif
                    </form>
                </div>
            </div>
        @endif

        <!-- Filtres Futuristes -->
        @if (!$showForm && !$isEdit)
            <div class="card-2050 mb-4 hover-lift">
                <div class="card-header-2050">
                    <h6 class="mb-0">
                        <i class="fas fa-filter me-2"></i>Filtres Intelligents
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="form-row-2050">
                        <div class="form-col-2050 col-md-3">
                            <div class="form-group-2050">
                                <label class="form-label-2050">Recherche générale</label>
                                <input type="text" wire:model.live.debounce.500ms="search"
                                    class="form-control-2050" placeholder="Immatriculation, marque, modèle...">
                            </div>
                        </div>

                        <div class="form-col-2050 col-md-2">
                            <div class="form-group-2050">
                                <label class="form-label-2050">Type</label>
                                <div wire:ignore>
                                    <select id="filter-type" class="form-control-2050 select2-2050">
                                        <option value="">Tous</option>
                                        <option value="propriete">Propriété</option>
                                        <option value="location">Location</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-col-2050 col-md-2">
                            <div class="form-group-2050">
                                <label class="form-label-2050">Statut</label>
                                <div wire:ignore>
                                    <select id="filter-statut" class="form-control-2050 select2-2050">
                                        <option value="">Tous</option>
                                        <option value="disponible">Disponible</option>
                                        <option value="en_mission">En mission</option>
                                        <option value="en_maintenance">En maintenance</option>
                                        <option value="hors_service">Hors service</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-col-2050 col-md-2">
                            <div class="form-group-2050">
                                <label class="form-label-2050">Marque</label>
                                <div wire:ignore>
                                    <select id="filter-marque" class="form-control-2050 select2-2050">
                                        <option value="">Toutes</option>
                                        @foreach ($marques as $marque)
                                            <option value="{{ $marque->id }}">{{ $marque->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-col-2050 col-md-2">
                            <div class="form-group-2050">
                                <label class="form-label-2050">Modèle</label>
                                <div wire:ignore>
                                    <select id="filter-modele" class="form-control-2050 select2-2050">
                                        <option value="">Tous</option>
                                        @foreach ($filterModeles as $modele)
                                            <option value="{{ $modele->id }}">{{ $modele->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-col-2050 col-md-1">
                            <div class="form-group-2050">
                                <label class="form-label-2050">&nbsp;</label>
                                <button wire:click="resetFilters" class="btn btn-outline-2050 w-100">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Ligne 2 - Dates -->
                    <div class="form-row-2050 mt-3">
                        <div class="form-col-2050 col-md-3">
                            <div class="form-group-2050">
                                <label class="form-label-2050">Date d'achat - Début</label>
                                <input type="date" wire:model.live="filterDateAchatDebut"
                                    class="form-control-2050">
                            </div>
                        </div>
                        <div class="form-col-2050 col-md-3">
                            <div class="form-group-2050">
                                <label class="form-label-2050">Date d'achat - Fin</label>
                                <input type="date" wire:model.live="filterDateAchatFin" class="form-control-2050">
                            </div>
                        </div>
                        <div class="form-col-2050 col-md-3">
                            <div class="form-group-2050">
                                <label class="form-label-2050">Année - Début</label>
                                <input type="number" wire:model.live="filterAnneeDebut" class="form-control-2050"
                                    min="1990" max="{{ date('Y') + 1 }}" placeholder="1990">
                            </div>
                        </div>
                        <div class="form-col-2050 col-md-3">
                            <div class="form-group-2050">
                                <label class="form-label-2050">Année - Fin</label>
                                <input type="number" wire:model.live="filterAnneeFin" class="form-control-2050"
                                    min="1990" max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Liste des véhicules Futuriste -->
        @if (!$showForm && !$isEdit)
            <div class="card-2050 hover-lift">
                <div class="card-header-2050">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Liste des véhicules
                            <span class="badge badge-success-2050 ms-2">{{ $vehicules->total() }}</span>
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
                                    <th><i class="fas fa-images me-2"></i>Photos</th>
                                    <th>
                                        <button wire:click="sortBy('immatriculation')"
                                            class="btn btn-link p-0 text-decoration-none">
                                            {{-- class="btn btn-link p-0 text-decoration-none text-black"> --}}
                                            <i class="fas fa-car me-2"></i>Véhicule
                                            @if ($sortField === 'immatriculation')
                                                <i
                                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted"></i>
                                            @endif
                                        </button>
                                    </th>
                                    <th>
                                        <button wire:click="sortBy('type')"
                                            class="btn btn-link p-0 text-decoration-none">
                                            <i class="fas fa-info-circle me-2"></i>Type
                                            @if ($sortField === 'type')
                                                <i
                                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted"></i>
                                            @endif
                                        </button>
                                    </th>
                                    <th>
                                        <button wire:click="sortBy('statut')"
                                            class="btn btn-link p-0 text-decoration-none">
                                            <i class="fas fa-chart-line me-2"></i>Statut
                                            @if ($sortField === 'statut')
                                                <i
                                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted"></i>
                                            @endif
                                        </button>
                                    </th>
                                    <th><i class="fas fa-cogs me-2"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vehicules as $vehicule)
                                    <tr class="animate-fade-in-up">
                                        <td>
                                            @if ($vehicule->photos->count() > 0)
                                                <div class="d-flex">
                                                    @foreach ($vehicule->photos->take(3) as $photo)
                                                        <div class="me-1">
                                                            <img src="{{ $photo->url }}" class="rounded"
                                                                style="width: 40px; height: 40px; object-fit: cover;"
                                                                alt="Photo">
                                                        </div>
                                                    @endforeach
                                                    @if ($vehicule->photos->count() > 3)
                                                        <div class="glass-effect rounded d-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px;">
                                                            <small
                                                                class="text-muted">+{{ $vehicule->photos->count() - 3 }}</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">Aucune photo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="glass-effect rounded-circle p-2 me-3">
                                                    <i class="fas fa-car text-gradient"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $vehicule->immatriculation }}</strong>
                                                    <br><small
                                                        class="text-muted">{{ $vehicule->marque->nom ?? 'N/A' }}
                                                        {{ $vehicule->modele->nom ?? '' }}</small>
                                                    @if ($vehicule->annee)
                                                        <br><small class="text-muted">{{ $vehicule->annee }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($vehicule->type === 'propriete')
                                                <span class="badge badge-primary-2050">
                                                    <i class="fas fa-home me-1"></i>Propriété
                                                    @if ($vehicule->prix_achat)
                                                        <br><small>{{ number_format($vehicule->prix_achat, 2) }}
                                                            €</small>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="badge badge-warning-2050">
                                                    <i class="fas fa-handshake me-1"></i>Location
                                                    @if ($vehicule->prix_location_jour)
                                                        <br><small>{{ number_format($vehicule->prix_location_jour, 2) }}
                                                            €/jour</small>
                                                    @endif
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($vehicule->statut === 'disponible')
                                                <span class="badge badge-success-2050">
                                                    <i class="fas fa-check me-1"></i>Disponible
                                                </span>
                                            @elseif($vehicule->statut === 'en_mission')
                                                <span class="badge badge-warning-2050">
                                                    <i class="fas fa-road me-1"></i>En mission
                                                </span>
                                            @elseif($vehicule->statut === 'en_maintenance')
                                                <span class="badge badge-danger-2050">
                                                    <i class="fas fa-tools me-1"></i>Maintenance
                                                </span>
                                            @else
                                                <span class="badge badge-danger-2050">
                                                    <i class="fas fa-times me-1"></i>Hors service
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group-actions">
                                                <button wire:click="showDetails({{ $vehicule->id }})"
                                                    class="btn btn-outline-2050 btn-sm" title="Détails">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button wire:click="edit({{ $vehicule->id }})"
                                                    class="btn btn-warning-2050 btn-sm" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button"
                                                    wire:click.prevent="confirmDelete({{ $vehicule->id }})"
                                                    class="btn btn-danger-2050 btn-sm" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <div class="glass-effect rounded-circle p-4 mx-auto mb-3"
                                                style="width: 80px; height: 80px;">
                                                <i class="fas fa-car text-gradient fs-2"></i>
                                            </div>
                                            <h5>Aucun véhicule trouvé</h5>
                                            <p class="mb-0">Ajoutez votre premier véhicule pour commencer</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                {{ $vehicules->links() }}
            </div>
    </div>

    <!-- Modal de détails -->
    @if ($showDetailsModal && $selectedVehicule)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1"
            data-bs-backdrop="true" data-bs-keyboard="true" wire:click.self="closeDetailsModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content card-2050">
                    <div class="modal-header card-header-2050">
                        <h5 class="modal-title">
                            <i class="fas fa-car me-2"></i>Détails du véhicule -
                            {{ $selectedVehicule->immatriculation }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeDetailsModal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="text-gradient mb-3">Informations générales</h6>
                                <div class="glass-effect p-3 rounded">
                                    <p><strong>Immatriculation:</strong> {{ $selectedVehicule->immatriculation }}</p>
                                    <p><strong>Marque:</strong> {{ $selectedVehicule->marque->nom ?? 'N/A' }}</p>
                                    <p><strong>Modèle:</strong> {{ $selectedVehicule->modele->nom ?? 'N/A' }}</p>
                                    <p><strong>Année:</strong> {{ $selectedVehicule->annee ?? 'N/A' }}</p>
                                    <p><strong>Couleur:</strong> {{ $selectedVehicule->couleur ?? 'N/A' }}</p>
                                    <p><strong>Kilométrage:</strong>
                                        {{ number_format($selectedVehicule->kilometrage ?? 0) }} km</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-gradient mb-3">Informations financières</h6>
                                <div class="glass-effect p-3 rounded">
                                    <p><strong>Type:</strong>
                                        @if ($selectedVehicule->type === 'propriete')
                                            <span class="badge badge-primary-2050">Propriété</span>
                                            @if ($selectedVehicule->prix_achat)
                                                <br><strong>Prix d'achat:</strong>
                                                {{ number_format($selectedVehicule->prix_achat, 2) }} €
                                            @endif
                                            @if ($selectedVehicule->date_achat)
                                                <br><strong>Date d'achat:</strong>
                                                {{ \Carbon\Carbon::parse($selectedVehicule->date_achat)->format('d/m/Y') }}
                                            @endif
                                        @else
                                            <span class="badge badge-warning-2050">Location</span>
                                            @if ($selectedVehicule->prix_location_jour)
                                                <br><strong>Prix location/jour:</strong>
                                                {{ number_format($selectedVehicule->prix_location_jour, 2) }} €
                                            @endif
                                            @if ($selectedVehicule->date_location)
                                                <br><strong>Date location:</strong>
                                                {{ \Carbon\Carbon::parse($selectedVehicule->date_location)->format('d/m/Y') }}
                                            @endif
                                            @if ($selectedVehicule->prix_location_jour && $selectedVehicule->date_location)
                                                <br><strong>Nombre de jours:</strong>
                                                {{ $selectedVehicule->nombre_jours_location }} jours
                                                <br><strong>Total à payer:</strong>
                                                <span
                                                    class="text-success fw-bold">{{ number_format($selectedVehicule->total_location, 2) }}
                                                    €</span>
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-gradient mb-3">Statut</h6>
                                <div class="glass-effect p-3 rounded">
                                    <p><strong>Statut:</strong>
                                        @if ($selectedVehicule->statut === 'disponible')
                                            <span class="badge badge-success-2050">Disponible</span>
                                        @elseif($selectedVehicule->statut === 'en_mission')
                                            <span class="badge badge-warning-2050">En mission</span>
                                        @elseif($selectedVehicule->statut === 'en_maintenance')
                                            <span class="badge badge-danger-2050">Maintenance</span>
                                        @else
                                            <span class="badge badge-danger-2050">Hors service</span>
                                        @endif
                                    </p>
                                    @if ($selectedVehicule->prochaine_revision)
                                        <p><strong>Prochaine révision:</strong>
                                            {{ \Carbon\Carbon::parse($selectedVehicule->prochaine_revision)->format('d/m/Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($selectedVehicule->photos->count() > 0)
                            <div class="mt-4">
                                <h6 class="text-gradient mb-3">Photos du véhicule
                                    ({{ $selectedVehicule->photos->count() }})</h6>
                                <div class="row">
                                    @foreach ($selectedVehicule->photos as $photo)
                                        <div class="col-md-3 mb-3">
                                            <div class="card-2050">
                                                <img src="{{ $photo->url }}" class="card-img-top photo-thumbnail"
                                                    style="height: 200px; object-fit: cover; cursor: pointer;"
                                                    data-image-src="{{ $photo->url }}"
                                                    data-image-name="{{ $photo->nom_fichier }}">
                                                <div class="card-body p-2">
                                                    <small class="text-muted">{{ $photo->nom_fichier }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer card-header-2050">
                        <button type="button" class="btn btn-outline-2050" wire:click="closeDetailsModal">
                            <i class="fas fa-times me-2"></i>Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal pour voir les photos en grand -->
    <div class="modal fade" id="photoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content card-2050">
                <div class="modal-header card-header-2050">
                    <h5 class="modal-title" id="photoModalTitle">Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="photoModalImage" src="" class="img-fluid" style="max-height: 70vh;">
                </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestionnaire d'événements pour les miniatures de photos
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('photo-thumbnail')) {
                    const imageSrc = e.target.getAttribute('data-image-src');
                    const fileName = e.target.getAttribute('data-image-name');

                    const photoModalImage = document.getElementById('photoModalImage');
                    const photoModalTitle = document.getElementById('photoModalTitle');
                    const photoModal = document.getElementById('photoModal');

                    if (photoModalImage && photoModalTitle && photoModal) {
                        photoModalImage.src = imageSrc;
                        photoModalTitle.textContent = fileName;

                        const modal = new bootstrap.Modal(photoModal);
                        modal.show();
                    }
                }
            });
        });

        // Fonction pour initialiser Select2 quand tout est prêt
        function initSelect2WhenReady() {
            console.log('=== INITIALISATION SELECT2 - VÉRIFICATION ===');
            console.log('jQuery disponible:', typeof $ !== 'undefined');
            console.log('Select2 disponible:', typeof $.fn.select2 !== 'undefined');
            console.log('Livewire disponible:', typeof Livewire !== 'undefined');

            if (typeof $ === 'undefined') {
                console.log('jQuery pas encore chargé, attente...');
                setTimeout(initSelect2WhenReady, 100);
                return;
            }

            if (typeof Livewire === 'undefined') {
                console.log('Livewire pas encore chargé, attente...');
                setTimeout(initSelect2WhenReady, 100);
                return;
            }

            console.log('=== TOUT EST PRÊT - INITIALISATION ===');
            initSelect2();
        }

        // BUG FIX 1 & 2: Synchronisation Select2 des filtres ET du formulaire sans flicker
        function initSelect2() {
            console.log('=== INITIALISATION SELECT2 FILTRES ET FORMULAIRE ===');

            // Initialiser Select2 pour les filtres ET le formulaire UNE SEULE FOIS
            function initAllSelect2() {
                console.log('=== INITIALISATION SELECT2 ===');

                // Vérifier que les éléments existent
                console.log('Filtres trouvés:', $('#filter-type, #filter-statut, #filter-marque, #filter-modele')
                    .length);
                console.log('Formulaire trouvé:', $(
                    '#form-type, #form-marque, #form-modele, #form-couleur, #form-statut').length);

                console.log('Initialisation Select2 filtres...');
                $('#filter-type, #filter-statut, #filter-marque, #filter-modele')
                    .select2({
                        placeholder: function() {
                            return $(this).find('option:first').text();
                        },
                        allowClear: true,
                        theme: 'bootstrap-5',
                        width: '100%',
                        dropdownCssClass: 'select2-dropdown-2050',
                        selectionCssClass: 'select2-selection-2050'
                    });

                console.log('Initialisation Select2 formulaire...');
                $('#form-type, #form-marque, #form-modele, #form-couleur, #form-statut')
                    .select2({
                        placeholder: function() {
                            return $(this).find('option:first').text();
                        },
                        allowClear: true,
                        theme: 'bootstrap-5',
                        width: '100%',
                        dropdownCssClass: 'select2-dropdown-2050',
                        selectionCssClass: 'select2-selection-2050'
                    });

                console.log('=== SELECT2 INITIALISÉ ===');
            }

            // Initialiser après un délai pour s'assurer que le DOM est prêt
            setTimeout(initAllSelect2, 300);

            // Synchroniser les valeurs Livewire -> Select2 (au chargement)
            Livewire.on('set-filter-values', (values) => {
                console.log('Mise à jour des valeurs des filtres:', values);
                if (values.filterType) $('#filter-type').val(values.filterType).trigger('change');
                if (values.filterStatut) $('#filter-statut').val(values.filterStatut).trigger('change');
                if (values.filterMarque) $('#filter-marque').val(values.filterMarque).trigger('change');
                if (values.filterModele) $('#filter-modele').val(values.filterModele).trigger('change');
            });

            // Synchroniser Select2 -> Livewire (quand l'utilisateur change)
            // FILTRES
            $('#filter-type').on('change', function() {
                const value = $(this).val();
                console.log('Filtre Type changé:', value);
                const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                    'wire:id'));
                if (livewireComponent) {
                    livewireComponent.set('filterType', value);
                    console.log('Filtre Type envoyé à Livewire');
                }
            });

            $('#filter-statut').on('change', function() {
                const value = $(this).val();
                console.log('Filtre Statut changé:', value);
                const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                    'wire:id'));
                if (livewireComponent) {
                    livewireComponent.set('filterStatut', value);
                    console.log('Filtre Statut envoyé à Livewire');
                }
            });

            $('#filter-marque').on('change', function() {
                const value = $(this).val();
                console.log('Filtre Marque changé:', value);
                const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                    'wire:id'));
                if (livewireComponent) {
                    livewireComponent.set('filterMarque', value);
                    console.log('Filtre Marque envoyé à Livewire');
                }
            });

            $('#filter-modele').on('change', function() {
                const value = $(this).val();
                console.log('Filtre Modèle changé:', value);
                const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                    'wire:id'));
                if (livewireComponent) {
                    livewireComponent.set('filterModele', value);
                    console.log('Filtre Modèle envoyé à Livewire');
                }
            });

            // FORMULAIRE
            $('#form-type').on('change', function() {
                const value = $(this).val();
                console.log('Form Type changé:', value);
                Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'))
                    .set('type', value);
            });

            $('#form-marque').on('change', function() {
                const value = $(this).val();
                console.log('=== FORM MARQUE CHANGÉ ===');
                console.log('Valeur sélectionnée:', value);

                const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                    'wire:id'));
                if (livewireComponent) {
                    console.log('Composant Livewire trouvé, envoi de la marque...');
                    livewireComponent.set('marque_id', value);
                    console.log('Marque envoyée à Livewire:', value);
                } else {
                    console.error('Composant Livewire non trouvé !');
                }
            });

            $('#form-modele').on('change', function() {
                const value = $(this).val();
                console.log('Form Modèle changé:', value);
                Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'))
                    .set('modele_id', value);
            });

            $('#form-couleur').on('change', function() {
                const value = $(this).val();
                console.log('Form Couleur changé:', value);
                Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'))
                    .set('couleur', value);
            });

            $('#form-statut').on('change', function() {
                const value = $(this).val();
                console.log('Form Statut changé:', value);
                Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'))
                    .set('statut', value);
            });

            // Mettre à jour les options des modèles quand la marque change
            Livewire.on('update-filter-modeles', (data) => {
                console.log('Mise à jour des modèles pour les filtres:', data);
                const $modeleSelect = $('#filter-modele');
                $modeleSelect.empty().append('<option value="">Tous</option>');

                // Vérifier le format des données
                let modeles = data;
                if (data && data.modeles) {
                    modeles = data.modeles;
                }

                if (modeles && Array.isArray(modeles) && modeles.length > 0) {
                    modeles.forEach(modele => {
                        $modeleSelect.append(`<option value="${modele.id}">${modele.nom}</option>`);
                    });
                    console.log('Modèles ajoutés au filtre:', modeles.length);
                } else {
                    console.log('Aucun modèle à ajouter au filtre');
                }

                $modeleSelect.trigger('change.select2');
            });

            // Mettre à jour les options des modèles du formulaire quand la marque change
            Livewire.on('update-form-modeles', (data) => {
                console.log('=== MISE À JOUR DES MODÈLES FORMULAIRE ===');
                console.log('Données reçues:', data);

                const $modeleSelect = $('#form-modele');
                console.log('Select modèles trouvé:', $modeleSelect.length);

                // Vider et reconstruire les options
                $modeleSelect.empty().append('<option value="">-- Sélectionner un modèle --</option>');

                // Vérifier le format des données
                let modeles = data;
                if (data && data.modeles) {
                    modeles = data.modeles;
                }

                if (modeles && Array.isArray(modeles) && modeles.length > 0) {
                    modeles.forEach(modele => {
                        $modeleSelect.append(`<option value="${modele.id}">${modele.nom}</option>`);
                    });
                    console.log('Options ajoutées:', modeles.length);
                } else {
                    console.log('Aucun modèle à ajouter');
                }

                // Réinitialiser Select2
                $modeleSelect.trigger('change.select2');
                console.log('Select2 modèles mis à jour');
            });

            // Synchroniser Livewire -> Select2 (quand les valeurs changent côté serveur)
            Livewire.on('sync-form-select2', (values) => {
                console.log('Synchronisation des valeurs du formulaire:', values);
                if (values.type) $('#form-type').val(values.type).trigger('change');
                if (values.marque_id) $('#form-marque').val(values.marque_id).trigger('change');
                if (values.modele_id) $('#form-modele').val(values.modele_id).trigger('change');
                if (values.couleur) $('#form-couleur').val(values.couleur).trigger('change');
                if (values.statut) $('#form-statut').val(values.statut).trigger('change');
            });

            // Réinitialiser les filtres
            Livewire.on('reset-filter-select2', () => {
                console.log('=== RÉINITIALISATION DES FILTRES SELECT2 ===');

                // Réinitialiser tous les Select2 des filtres
                $('#filter-type').val('').trigger('change');
                $('#filter-statut').val('').trigger('change');
                $('#filter-marque').val('').trigger('change');
                $('#filter-modele').val('').trigger('change');

                console.log('Tous les Select2 des filtres ont été réinitialisés');
            });
        }

        // Démarrer l'initialisation
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== DOM CONTENT LOADED ===');
            initSelect2WhenReady();

            // Observer pour détecter quand le formulaire apparaît
            let observer = null;
            let eventsAttached = false;

            function startObserver() {
                if (observer) return; // Éviter les doublons

                observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'childList') {
                            // Vérifier si le formulaire est maintenant présent
                            if ($('#form-marque').length > 0 && !eventsAttached) {
                                console.log(
                                    '=== FORMULAIRE DÉTECTÉ - ATTACHEMENT DES ÉVÉNEMENTS ===');
                                attachFormEvents();
                                eventsAttached = true;
                                observer.disconnect(); // Arrêter l'observation
                                observer = null;
                            }
                        }
                    });
                });

                // Observer le contenu de la page
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }

            // Démarrer l'observation
            startObserver();

            // Fonction pour attacher les événements du formulaire
            function attachFormEvents() {
                console.log('=== ATTACHEMENT DES ÉVÉNEMENTS FORMULAIRE ===');

                // Marque
                $(document).off('change', '#form-marque').on('change', '#form-marque', function() {
                    const value = $(this).val();
                    console.log('=== FORM MARQUE CHANGÉ ===');
                    console.log('Valeur sélectionnée:', value);

                    const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]')
                        .getAttribute('wire:id'));
                    if (livewireComponent) {
                        console.log('Composant Livewire trouvé, envoi de la marque...');
                        livewireComponent.set('marque_id', value);
                        console.log('Marque envoyée à Livewire:', value);
                    } else {
                        console.error('Composant Livewire non trouvé !');
                    }
                });

                // Modèle
                $(document).off('change', '#form-modele').on('change', '#form-modele', function() {
                    const value = $(this).val();
                    console.log('Form Modèle changé:', value);
                    const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]')
                        .getAttribute('wire:id'));
                    if (livewireComponent) {
                        livewireComponent.set('modele_id', value);
                        console.log('Modèle envoyé à Livewire:', value);
                    }
                });

                // Type
                $(document).off('change', '#form-type').on('change', '#form-type', function() {
                    const value = $(this).val();
                    console.log('Form Type changé:', value);
                    const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]')
                        .getAttribute('wire:id'));
                    if (livewireComponent) {
                        livewireComponent.set('type', value);
                        console.log('Type envoyé à Livewire:', value);
                    }
                });

                // Couleur
                $(document).off('change', '#form-couleur').on('change', '#form-couleur', function() {
                    const value = $(this).val();
                    console.log('Form Couleur changé:', value);
                    const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]')
                        .getAttribute('wire:id'));
                    if (livewireComponent) {
                        livewireComponent.set('couleur', value);
                        console.log('Couleur envoyée à Livewire:', value);
                    }
                });

                // Statut
                $(document).off('change', '#form-statut').on('change', '#form-statut', function() {
                    const value = $(this).val();
                    console.log('Form Statut changé:', value);
                    const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]')
                        .getAttribute('wire:id'));
                    if (livewireComponent) {
                        livewireComponent.set('statut', value);
                        console.log('Statut envoyé à Livewire:', value);
                    }
                });

                console.log('Événements du formulaire attachés avec succès');
            }
        });
    </script>

    <!-- Modal Confirmation Suppression -->
    @if ($showDeleteModal && $vehiculeToDelete)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5); z-index: 9999;"
            wire:click.self="cancelDelete">
            <div class="modal-dialog">
                <div class="modal-content card-2050">
                    <div class="modal-header card-header-2050">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Confirmation de suppression
                        </h5>
                        <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="text-center">
                            <i class="fas fa-car fa-3x text-danger mb-3"></i>
                            <h5>Êtes-vous sûr de vouloir supprimer ce véhicule ?</h5>
                            <p class="text-muted">Cette action est irréversible et supprimera également toutes les
                                photos associées.</p>
                        </div>
                    </div>
                    <div class="modal-footer card-header-2050">
                        <button type="button" class="btn btn-outline-2050" wire:click="cancelDelete">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="button" class="btn btn-danger-2050"
                            wire:click="destroy({{ $vehiculeToDelete }})">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endif
</div>
