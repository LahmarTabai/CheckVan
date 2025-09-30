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

        <!-- Bouton Ajouter Affectation -->
        @if (!$showForm && !$isEdit)
            <div class="d-flex justify-content-end mb-4">
                <button type="button" wire:click="showAddForm" class="btn btn-primary-2050">
                    <i class="fas fa-plus me-2"></i>Ajouter une affectation
                </button>
            </div>
        @endif

        <!-- Formulaire Futuriste -->
        @if ($showForm || $isEdit)
            <div class="card-2050 mb-4 hover-lift">
                <div class="card-header-2050">
                    <h6 class="mb-0">
                        <i
                            class="fas fa-plus me-2"></i>{{ $isEdit ? 'Modifier l\'affectation' : 'Nouvelle affectation' }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'save' }}">
                        <div class="form-section-2050">
                            <h6 class="section-title-2050">
                                <i class="fas fa-user-tie me-2"></i>Sélection du chauffeur
                            </h6>

                            <div class="form-row-2050">
                                <div class="form-col-2050 col-md-4">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">
                                            Chauffeur <span class="required">*</span>
                                        </label>
                                        <div wire:ignore>
                                            <select id="form-chauffeur" class="form-control-2050 select2-2050">
                                                <option value="">-- Sélectionner un chauffeur --</option>
                                                @foreach ($chauffeurs as $c)
                                                    <option value="{{ $c->user_id }}">{{ $c->nom }}
                                                        {{ $c->prenom }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('chauffeur_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-col-2050 col-md-4">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">
                                            Véhicule <span class="required">*</span>
                                        </label>
                                        <div wire:ignore>
                                            <select id="form-vehicule" class="form-control-2050 select2-2050">
                                                <option value="">-- Sélectionner un véhicule --</option>
                                                @foreach ($vehicules as $v)
                                                    <option value="{{ $v->id }}">
                                                        {{ $v->marque->nom ?? 'N/A' }} {{ $v->modele->nom ?? '' }} -
                                                        {{ $v->immatriculation }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <small class="form-help-2050">Véhicules disponibles :
                                            {{ $vehicules->count() }}</small>
                                        @error('vehicule_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-col-2050 col-md-4">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">Statut</label>
                                        <div wire:ignore>
                                            <select id="form-status" class="form-control-2050 select2-2050">
                                                <option value="en_cours">En cours</option>
                                                <option value="terminée">Terminée</option>
                                            </select>
                                        </div>
                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section-2050">
                            <h6 class="section-title-2050">
                                <i class="fas fa-calendar-alt me-2"></i>Planning de l'affectation
                            </h6>

                            <div class="form-row-2050">
                                <div class="form-col-2050 col-md-6">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">
                                            Date de début <span class="required">*</span>
                                        </label>
                                        <input type="date" wire:model="date_debut" class="form-control-2050">
                                        <small class="form-help-2050">Date de début de l'affectation</small>
                                        @error('date_debut')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-col-2050 col-md-6">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">Date de fin</label>
                                        <input type="date" wire:model="date_fin" class="form-control-2050">
                                        <small class="form-help-2050">Date de fin de l'affectation (optionnel)</small>
                                        @error('date_fin')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section-2050">
                            <h6 class="section-title-2050">
                                <i class="fas fa-align-left me-2"></i>Description
                            </h6>

                            <div class="form-row-2050">
                                <div class="form-col-2050 col-12">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">Description</label>
                                        <textarea wire:model="description" class="form-control-2050" rows="3"
                                            placeholder="Description de l'affectation..."></textarea>
                                        <small class="form-help-2050">Décrivez les détails de cette affectation</small>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions-2050">
                            <button type="submit" class="btn btn-primary-2050">
                                <i class="fas fa-save me-2"></i>{{ $isEdit ? 'Modifier' : 'Ajouter' }} Affectation
                            </button>
                            @if ($isEdit)
                                <button type="button" wire:click="resetForm" class="btn btn-outline-2050">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <script>
                // BUG FIX: Synchronisation Select2 sans flicker pour affectations
                function initSelect2() {
                    console.log('=== INITIALISATION SELECT2 AFFECTATIONS ===');

                    // Initialiser Select2 pour les filtres ET le formulaire
                    function initAllSelect2() {
                        console.log('=== INITIALISATION SELECT2 ===');

                        // Vérifier que les éléments existent
                        console.log('Filtres trouvés:', $('#filter-status, #filter-chauffeur').length);
                        console.log('Formulaire trouvé:', $('#form-chauffeur, #form-vehicule, #form-status').length);

                        console.log('Initialisation Select2 filtres...');
                        $('#filter-status, #filter-chauffeur')
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
                        $('#form-chauffeur, #form-vehicule, #form-status')
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
                        if (values.filterStatus) $('#filter-status').val(values.filterStatus).trigger('change');
                        if (values.filterChauffeur) $('#filter-chauffeur').val(values.filterChauffeur).trigger('change');
                    });

                    // Synchroniser Select2 -> Livewire (quand l'utilisateur change)
                    // FILTRES
                    $('#filter-status').on('change', function() {
                        const value = $(this).val();
                        console.log('Filtre Status changé:', value);
                        const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                            'wire:id'));
                        if (livewireComponent) {
                            livewireComponent.set('filterStatus', value);
                            console.log('Filtre Status envoyé à Livewire');
                        }
                    });

                    $('#filter-chauffeur').on('change', function() {
                        const value = $(this).val();
                        console.log('Filtre Chauffeur changé:', value);
                        const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                            'wire:id'));
                        if (livewireComponent) {
                            livewireComponent.set('filterChauffeur', value);
                            console.log('Filtre Chauffeur envoyé à Livewire');
                        }
                    });

                    // FORMULAIRE
                    $('#form-chauffeur').on('change', function() {
                        const value = $(this).val();
                        console.log('Form Chauffeur changé:', value);
                        const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                            'wire:id'));
                        if (livewireComponent) {
                            livewireComponent.set('chauffeur_id', value);
                            console.log('Form Chauffeur envoyé à Livewire');
                        }
                    });

                    $('#form-vehicule').on('change', function() {
                        const value = $(this).val();
                        console.log('Form Véhicule changé:', value);
                        const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                            'wire:id'));
                        if (livewireComponent) {
                            livewireComponent.set('vehicule_id', value);
                            console.log('Form Véhicule envoyé à Livewire');
                        }
                    });

                    $('#form-status').on('change', function() {
                        const value = $(this).val();
                        console.log('Form Status changé:', value);
                        const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                            'wire:id'));
                        if (livewireComponent) {
                            livewireComponent.set('status', value);
                            console.log('Form Status envoyé à Livewire');
                        }
                    });

                    // Synchroniser Livewire -> Select2 (quand les valeurs changent côté serveur)
                    Livewire.on('sync-form-select2', (values) => {
                        console.log('Synchronisation des valeurs du formulaire:', values);
                        if (values.chauffeur_id) $('#form-chauffeur').val(values.chauffeur_id).trigger('change');
                        if (values.vehicule_id) $('#form-vehicule').val(values.vehicule_id).trigger('change');
                        if (values.status) $('#form-status').val(values.status).trigger('change');
                    });

                    // Réinitialiser les filtres
                    Livewire.on('reset-filter-select2', () => {
                        console.log('=== RÉINITIALISATION DES FILTRES SELECT2 ===');
                        $('#filter-status, #filter-chauffeur').val('').trigger('change');
                        console.log('Tous les Select2 des filtres ont été réinitialisés');
                    });
                }

                // Fonction pour vérifier que tout est prêt
                function initSelect2WhenReady() {
                    console.log('=== INITIALISATION SELECT2 - VÉRIFICATION ===');
                    console.log('jQuery disponible:', typeof $ !== 'undefined');
                    console.log('Select2 disponible:', typeof $.fn.select2 !== 'undefined');
                    console.log('Livewire disponible:', typeof Livewire !== 'undefined');

                    if (typeof $ === 'undefined') {
                        console.log('jQuery non disponible, nouvelle tentative dans 100ms...');
                        setTimeout(initSelect2WhenReady, 100);
                        return;
                    }

                    if (typeof $.fn.select2 === 'undefined') {
                        console.log('Select2 non disponible, nouvelle tentative dans 100ms...');
                        setTimeout(initSelect2WhenReady, 100);
                        return;
                    }

                    if (typeof Livewire === 'undefined') {
                        console.log('Livewire non disponible, nouvelle tentative dans 100ms...');
                        setTimeout(initSelect2WhenReady, 100);
                        return;
                    }

                    console.log('=== TOUT EST PRÊT - INITIALISATION ===');
                    initSelect2();
                }

                // Observer pour détecter quand le formulaire apparaît
                let observer = null;
                let eventsAttached = false;

                function startObserver() {
                    if (observer) return; // Éviter les doublons

                    observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.type === 'childList') {
                                // Vérifier si le formulaire est maintenant présent
                                if ($('#form-chauffeur').length > 0 && !eventsAttached) {
                                    console.log('=== FORMULAIRE DÉTECTÉ - ATTACHEMENT DES ÉVÉNEMENTS ===');
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

                // Fonction pour attacher les événements du formulaire
                function attachFormEvents() {
                    console.log('=== ATTACHEMENT DES ÉVÉNEMENTS FORMULAIRE ===');

                    // Chauffeur
                    $(document).off('change', '#form-chauffeur').on('change', '#form-chauffeur', function() {
                        const value = $(this).val();
                        console.log('Form Chauffeur changé:', value);
                        const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                            'wire:id'));
                        if (livewireComponent) {
                            livewireComponent.set('chauffeur_id', value);
                            console.log('Form Chauffeur envoyé à Livewire');
                        }
                    });

                    // Véhicule
                    $(document).off('change', '#form-vehicule').on('change', '#form-vehicule', function() {
                        const value = $(this).val();
                        console.log('Form Véhicule changé:', value);
                        const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                            'wire:id'));
                        if (livewireComponent) {
                            livewireComponent.set('vehicule_id', value);
                            console.log('Form Véhicule envoyé à Livewire');
                        }
                    });

                    // Status
                    $(document).off('change', '#form-status').on('change', '#form-status', function() {
                        const value = $(this).val();
                        console.log('Form Status changé:', value);
                        const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                            'wire:id'));
                        if (livewireComponent) {
                            livewireComponent.set('status', value);
                            console.log('Form Status envoyé à Livewire');
                        }
                    });

                    console.log('Événements du formulaire attachés avec succès');
                }

                // Démarrer l'observation
                startObserver();

                // Démarrer l'initialisation
                document.addEventListener('DOMContentLoaded', function() {
                    console.log('=== DOM CONTENT LOADED ===');
                    initSelect2WhenReady();
                });
            </script>
        @endif

        <!-- Filtres Futuristes -->
        <div class="card-2050 mb-4 hover-lift">
            <div class="card-header-2050">
                <h6 class="mb-0">
                    <i class="fas fa-filter me-2"></i>Filtres Intelligents
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="form-row-2050">
                    <div class="form-col-2050 col-md-4">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Statut</label>
                            <div wire:ignore>
                                <select id="filter-status" class="form-control-2050 select2-2050">
                                    <option value="">Tous</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="terminée">Terminée</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-col-2050 col-md-4">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Chauffeur</label>
                            <div wire:ignore>
                                <select id="filter-chauffeur" class="form-control-2050 select2-2050">
                                    <option value="">Tous</option>
                                    @foreach ($chauffeurs as $c)
                                        <option value="{{ $c->user_id }}">{{ $c->nom }}
                                            {{ $c->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-col-2050 col-md-4">
                        <div class="form-group-2050">
                            <label class="form-label-2050">&nbsp;</label>
                            <button wire:click="resetFilters" class="btn btn-outline-2050 w-100">
                                <i class="fas fa-times me-2"></i>Effacer les filtres
                            </button>
                        </div>
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
                                <th class="text-primary"><i class="fas fa-file-text me-2"></i>Description
                                </th>
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
                                            <button type="button"
                                                wire:click.prevent="confirmDelete({{ $a->id }})"
                                                class="btn btn-danger-2050 btn-sm" title="Supprimer">
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
                                        <p class="mb-0">Créez votre première affectation pour commencer
                                        </p>
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

        <!-- Modal Confirmation Suppression -->
        @if ($showDeleteModal && $affectationToDelete)
            <div class="modal fade show d-block" tabindex="-1"
                style="background-color: rgba(0,0,0,0.5); z-index: 9999;" wire:click.self="cancelDelete">
                <div class="modal-dialog">
                    <div class="modal-content card-2050">
                        <div class="modal-header card-header-2050">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Confirmation
                                de
                                suppression
                            </h5>
                            <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="text-center">
                                <i class="fas fa-link fa-3x text-danger mb-3"></i>
                                <h5>Êtes-vous sûr de vouloir supprimer cette affectation ?</h5>
                                <p class="text-muted">Cette action est irréversible.</p>
                            </div>
                        </div>
                        <div class="modal-footer card-header-2050">
                            <button type="button" class="btn btn-outline-2050" wire:click="cancelDelete">
                                <i class="fas fa-times me-2"></i>Annuler
                            </button>
                            <button type="button" class="btn btn-danger-2050"
                                wire:click="delete({{ $affectationToDelete }})">
                                <i class="fas fa-trash me-2"></i>Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
