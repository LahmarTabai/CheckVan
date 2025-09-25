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

        <!-- Formulaire Futuriste -->
        <div class="card-2050 mb-4 hover-lift">
            <div class="card-header-2050">
                <h6 class="mb-0">
                    <i class="fas fa-plus me-2"></i>{{ $isEdit ? 'Modifier l\'affectation' : 'Nouvelle affectation' }}
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
                                    <select wire:model.live="chauffeur_id" class="form-control-2050 select2-2050">
                                        <option value="">-- Sélectionner un chauffeur --</option>
                                        @foreach ($chauffeurs as $c)
                                            <option value="{{ $c->user_id }}">{{ $c->nom }} {{ $c->prenom }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                    <select wire:model.live="vehicule_id" class="form-control-2050 select2-2050">
                                        <option value="">-- Sélectionner un véhicule --</option>
                                        @foreach ($vehicules as $v)
                                            <option value="{{ $v->id }}">
                                                {{ $v->marque->nom ?? 'N/A' }} {{ $v->modele->nom ?? '' }} -
                                                {{ $v->immatriculation }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                    <select wire:model.live="status" class="form-control-2050 select2-2050">
                                        <option value="en_cours">En cours</option>
                                        <option value="terminée">Terminée</option>
                                    </select>
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
            // Fonction pour synchroniser les valeurs Select2 avant soumission
            function syncSelect2Values() {
                console.log('=== SYNC SELECT2 VALUES AFFECTATIONS ===');

                try {
                    const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                    if (livewireComponent) {
                        // Synchroniser le chauffeur
                        const chauffeurValue = $('select[wire\\:model\\.live="chauffeur_id"]').val();
                        console.log('Chauffeur Select2:', chauffeurValue);
                        if (chauffeurValue) {
                            livewireComponent.set('chauffeur_id', chauffeurValue);
                            console.log('Chauffeur synchronisé vers Livewire');
                        }

                        // Synchroniser le véhicule
                        const vehiculeValue = $('select[wire\\:model\\.live="vehicule_id"]').val();
                        console.log('Véhicule Select2:', vehiculeValue);
                        if (vehiculeValue) {
                            livewireComponent.set('vehicule_id', vehiculeValue);
                            console.log('Véhicule synchronisé vers Livewire');
                        }

                        // Synchroniser le statut
                        const statusValue = $('select[wire\\:model\\.live="status"]').val();
                        console.log('Statut Select2:', statusValue);
                        if (statusValue) {
                            livewireComponent.set('status', statusValue);
                            console.log('Statut synchronisé vers Livewire');
                        }

                        console.log('=== SYNC TERMINÉ ===');
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

                            // Synchroniser le chauffeur
                            const chauffeurValue = livewireComponent.get('chauffeur_id');
                            console.log('Chauffeur Livewire:', chauffeurValue);
                            if (chauffeurValue) {
                                $('select[wire\\:model\\.live="chauffeur_id"]').val(chauffeurValue).trigger(
                                    'change');
                                console.log('Chauffeur Select2 mis à jour vers:', chauffeurValue);
                            }

                            // Synchroniser le véhicule
                            const vehiculeValue = livewireComponent.get('vehicule_id');
                            console.log('Véhicule Livewire:', vehiculeValue);
                            if (vehiculeValue) {
                                $('select[wire\\:model\\.live="vehicule_id"]').val(vehiculeValue).trigger(
                                    'change');
                                console.log('Véhicule Select2 mis à jour vers:', vehiculeValue);
                            }

                            // Synchroniser le statut
                            const statusValue = livewireComponent.get('status');
                            console.log('Statut Livewire:', statusValue);
                            if (statusValue) {
                                $('select[wire\\:model\\.live="status"]').val(statusValue).trigger('change');
                                console.log('Statut Select2 mis à jour vers:', statusValue);
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
                $('form[wire\\:submit\\.prevent="save"]').on('submit', function(e) {
                    e.preventDefault();
                    console.log('=== INTERCEPTION SOUMISSION AFFECTATIONS ===');

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
                                    livewireComponent.call('save');
                                }
                            }
                        }, 500);
                    }
                });
            });
        </script>

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
                            <select wire:model.live="filterStatus" class="form-control-2050 select2-2050">
                                <option value="">Tous</option>
                                <option value="en_cours">En cours</option>
                                <option value="terminée">Terminée</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-col-2050 col-md-4">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Chauffeur</label>
                            <select wire:model.live="filterChauffeur" class="form-control-2050 select2-2050">
                                <option value="">Tous</option>
                                @foreach ($chauffeurs as $c)
                                    <option value="{{ $c->user_id }}">{{ $c->nom }} {{ $c->prenom }}
                                    </option>
                                @endforeach
                            </select>
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
                                <th class="text-primary"><i class="fas fa-file-text me-2"></i>Description</th>
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
                                        <p class="mb-0">Créez votre première affectation pour commencer</p>
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
                                <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Confirmation de
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
        @endif
    </div>
</div>
