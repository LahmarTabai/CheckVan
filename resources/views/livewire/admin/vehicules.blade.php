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

        <!-- Formulaire Futuriste -->
        <div class="card-2050 mb-4 hover-lift">
            <div class="card-header-2050">
                <h6 class="mb-0">
                    <i class="fas fa-plus me-2"></i>{{ $isEdit ? 'Modifier le véhicule' : 'Ajouter un véhicule' }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
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
                                    <select wire:model.live="type" class="form-control-2050 select2-2050">
                                        <option value="">-- Sélectionner le type --</option>
                                        <option value="propriete">Propriété</option>
                                        <option value="location">Location</option>
                                    </select>
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
                                    <select wire:model.live="marque_id" class="form-control-2050 select2-2050"
                                        onchange="Livewire.dispatch('marque-changed', { marqueId: this.value })">
                                        <option value="">-- Sélectionner une marque --</option>
                                        @foreach ($marques as $marque)
                                            <option value="{{ $marque->id }}">{{ $marque->nom }}</option>
                                        @endforeach
                                    </select>
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

                                    <select wire:model="modele_id" class="form-control-2050 select2-2050"
                                        @disabled(!$marque_id)>
                                        <option value="">-- Sélectionner un modèle --</option>
                                        @foreach ($formModeles as $modele)
                                            <option wire:key="form-modele-{{ $modele->id }}"
                                                value="{{ $modele->id }}">{{ $modele->nom }}</option>
                                        @endforeach
                                    </select>
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
                                    <select wire:model="couleur" class="form-control-2050 select2-2050">
                                        <option value="">-- Sélectionner une couleur --</option>
                                        @foreach ($couleurs as $couleur)
                                            <option value="{{ $couleur }}">{{ $couleur }}</option>
                                        @endforeach
                                    </select>
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
                                    <input type="number" wire:model="annee" class="form-control-2050" min="1990"
                                        max="{{ date('Y') + 1 }}" placeholder="2024">
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
                                    <select wire:model="statut" class="form-control-2050 select2-2050">
                                        <option value="">-- Sélectionner le statut --</option>
                                        <option value="disponible">Disponible</option>
                                        <option value="en_mission">En mission</option>
                                        <option value="en_maintenance">En maintenance</option>
                                        <option value="hors_service">Hors service</option>
                                    </select>
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
                                    <input type="date" wire:model="prochaine_revision" class="form-control-2050">
                                    <small class="form-help-2050">Date de la prochaine révision prévue</small>
                                    @error('prochaine_revision')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-col-2050 col-md-3">
                                <div class="form-group-2050">
                                    <label class="form-label-2050">Dernière révision</label>
                                    <input type="date" wire:model="derniere_revision" class="form-control-2050">
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
                                    <small class="form-help-2050">Informations complémentaires sur le véhicule</small>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($type === 'propriete')
                        <div class="form-section-2050">
                            <h6 class="section-title-2050">
                                <i class="fas fa-euro-sign me-2"></i>Informations d'achat
                            </h6>

                            <div class="form-row-2050">
                                <div class="form-col-2050 col-md-6">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">Prix d'achat (€)</label>
                                        <input type="number" wire:model="prix_achat" class="form-control-2050"
                                            step="0.01" placeholder="0.00">
                                        <small class="form-help-2050">Prix d'achat du véhicule en euros</small>
                                        @error('prix_achat')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-col-2050 col-md-6">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">Date d'achat</label>
                                        <input type="date" wire:model="date_achat" class="form-control-2050">
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
                                        <input type="number" wire:model="prix_location" class="form-control-2050"
                                            step="0.01" placeholder="0.00">
                                        <small class="form-help-2050">Coût de location par jour en euros</small>
                                        @error('prix_location')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-col-2050 col-md-6">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">Date de location</label>
                                        <input type="date" wire:model="date_location" class="form-control-2050">
                                        <small class="form-help-2050">Date de début de la location</small>
                                        @error('date_location')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

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
                                    <small class="form-help-2050">Formats acceptés : JPG, PNG, GIF. Taille max : 8MB
                                        par photo.</small>
                                    @error('photos.*')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
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
                        @endif
                </form>
            </div>
        </div>

        <!-- Filtres Futuristes -->
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
                            <input type="text" wire:model.live="search" class="form-control-2050"
                                placeholder="Immatriculation, marque, modèle...">
                        </div>
                    </div>

                    <div class="form-col-2050 col-md-2">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Type</label>
                            <select wire:model.live="filterType" class="form-control-2050 select2-2050">
                                <option value="">Tous</option>
                                <option value="propriete">Propriété</option>
                                <option value="location">Location</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-col-2050 col-md-2">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Statut</label>
                            <select wire:model.live="filterStatut" class="form-control-2050 select2-2050">
                                <option value="">Tous</option>
                                <option value="disponible">Disponible</option>
                                <option value="en_mission">En mission</option>
                                <option value="en_maintenance">En maintenance</option>
                                <option value="hors_service">Hors service</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-col-2050 col-md-2">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Marque</label>
                            <select wire:model.live="filterMarque" class="form-control-2050 select2-2050">
                                <option value="">Toutes</option>
                                @foreach ($marques as $marque)
                                    <option value="{{ $marque->id }}">{{ $marque->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-col-2050 col-md-2">
                        <div class="form-group-2050">
                            <label class="form-label-2050">Modèle</label>
                            <select wire:model.live="filterModele" class="form-control-2050 select2-2050"
                                @disabled(!$filterMarque)>
                                <option value="">Tous</option>
                                @foreach ($filterModeles as $modele)
                                    <option wire:key="filter-modele-{{ $modele->id }}"
                                        value="{{ $modele->id }}">{{ $modele->nom }}</option>
                                @endforeach
                            </select>
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
            </div>
        </div>

        <!-- Liste des véhicules Futuriste -->
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
                                    <button wire:click="sortBy('type')" class="btn btn-link p-0 text-decoration-none">
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
                                                <br><small class="text-muted">{{ $vehicule->marque->nom ?? 'N/A' }}
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
                                            </span>
                                        @else
                                            <span class="badge badge-warning-2050">
                                                <i class="fas fa-handshake me-1"></i>Location
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
                                <h6 class="text-gradient mb-3">Statut et type</h6>
                                <div class="glass-effect p-3 rounded">
                                    <p><strong>Type:</strong>
                                        @if ($selectedVehicule->type === 'propriete')
                                            <span class="badge badge-primary-2050">Propriété</span>
                                        @else
                                            <span class="badge badge-warning-2050">Location</span>
                                        @endif
                                    </p>
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
</div>
