<div>
    <div class="p-4">
        <h2 class="h4 mb-4">Gestion des Véhicules</h2>

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif




        <!-- Formulaire -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ $isEdit ? 'Modifier le véhicule' : 'Ajouter un véhicule' }}</h5>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                    <div class="row">
                        <!-- Marque et Modèle -->
                        <div class="col-md-3">
                            <label class="form-label">Marque *</label>
                            <select wire:model.live="marque_id" class="form-control">
                                <option value="">-- Sélectionner une marque --</option>
                                @foreach ($marques as $marque)
                                    <option value="{{ $marque->id }}">{{ $marque->nom }}</option>
                                @endforeach
                            </select>
                            @error('marque_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Modèle *</label>
                            <select wire:model="modele_id" class="form-control" {{ !$marque_id ? 'disabled' : '' }}>
                                <option value="">-- Sélectionner un modèle --</option>
                                @if ($marque_id && $modeles->isEmpty())
                                    <option value="" disabled>Chargement des modèles...</option>
                                @else
                                    @foreach ($modeles as $modele)
                                        <option value="{{ $modele->id }}">{{ $modele->nom }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('modele_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Immatriculation *</label>
                            <input type="text" wire:model.defer="immatriculation" class="form-control"
                                placeholder="AB-123-CD">
                            @error('immatriculation')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Type *</label>
                            <select wire:model.live="type" class="form-control">
                                <option value="propriete">Propriété</option>
                                <option value="location">Location</option>
                            </select>
                            @error('type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-2">
                            <label class="form-label">Année</label>
                            <input type="number" wire:model.defer="annee" class="form-control" min="1990"
                                max="{{ date('Y') + 1 }}">
                            @error('annee')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Couleur</label>
                            <select wire:model.defer="couleur" class="form-control">
                                <option value="">-- Sélectionner une couleur --</option>
                                @foreach ($couleurs as $couleurOption)
                                    <option value="{{ $couleurOption }}">{{ $couleurOption }}</option>
                                @endforeach
                            </select>
                            @error('couleur')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Kilométrage</label>
                            <input type="number" wire:model.defer="kilometrage" class="form-control" min="0"
                                placeholder="0">
                            @error('kilometrage')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Statut *</label>
                            <select wire:model="statut" class="form-control">
                                <option value="disponible">Disponible</option>
                                <option value="en_mission">En mission</option>
                                <option value="en_maintenance">En maintenance</option>
                                <option value="hors_service">Hors service</option>
                            </select>
                            @error('statut')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        @if ($type === 'propriete')
                            <div class="col-md-2">
                                <label class="form-label">Prix d'achat (€)</label>
                                <input type="number" wire:model.defer="prix_achat" class="form-control"
                                    min="0" step="0.01" placeholder="25000">
                                @error('prix_achat')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date d'achat</label>
                                <input type="date" wire:model.defer="date_achat" class="form-control">
                                @error('date_achat')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        @else
                            <div class="col-md-2">
                                <label class="form-label">Prix de location/jour (€)</label>
                                <input type="number" wire:model.defer="prix_achat" class="form-control"
                                    min="0" step="0.01" placeholder="50">
                                @error('prix_achat')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date de location</label>
                                <input type="date" wire:model.defer="date_achat" class="form-control">
                                @error('date_achat')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label">N° Chassis</label>
                            <input type="text" wire:model.defer="numero_chassis" class="form-control">
                            @error('numero_chassis')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">N° Moteur</label>
                            <input type="text" wire:model.defer="numero_moteur" class="form-control">
                            @error('numero_moteur')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Dernière révision</label>
                            <input type="date" wire:model.defer="derniere_revision" class="form-control">
                            @error('derniere_revision')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Prochaine révision</label>
                            <input type="date" wire:model.defer="prochaine_revision" class="form-control">
                            @error('prochaine_revision')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea wire:model.defer="description" class="form-control" rows="2"
                                placeholder="Description du véhicule..."></textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label">Photos (plusieurs fichiers possibles)</label>
                            <input type="file" wire:model="photos" class="form-control" multiple
                                accept="image/*">
                            @error('photos.*')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <small class="text-muted">Formats acceptés : JPG, PNG, GIF. Taille max : 8MB par
                                photo.</small>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-primary" type="submit">
                            {{ $isEdit ? 'Modifier' : 'Ajouter' }} Véhicule
                        </button>
                        @if ($isEdit)
                            <button wire:click="resetForm" type="button" class="btn btn-secondary">Annuler</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>


        <!-- Filtres et recherche -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">🔍 Filtres de recherche</h6>
            </div>
            <div class="card-body">
                <div class="row">
                     <div class="col-md-3">
                         <label class="form-label">Recherche générale</label>
                         <input type="text" wire:model.live="search" class="form-control"
                             placeholder="Immatriculation, marque, modèle...">
                     </div>
                    <div class="col-md-2">
                        <label class="form-label">Type</label>
                        <select wire:model.live="filterType" class="form-control">
                            <option value="">Tous</option>
                            <option value="propriete">Propriété</option>
                            <option value="location">Location</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Statut</label>
                        <select wire:model.live="filterStatut" class="form-control">
                            <option value="">Tous</option>
                            <option value="disponible">Disponible</option>
                            <option value="en_mission">En mission</option>
                            <option value="en_maintenance">En maintenance</option>
                            <option value="hors_service">Hors service</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Marque</label>
                        <select wire:model.live="filterMarque" class="form-control">
                            <option value="">Toutes</option>
                            @foreach ($marques as $marque)
                            <option value="{{ $marque->id }}">{{ $marque->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Modèle</label>
                        <select wire:model.live="filterModele" class="form-control">
                            <option value="">Tous</option>
                            @foreach ($modeles as $modele)
                            <option value="{{ $modele->id }}">{{ $modele->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Année</label>
                        <select wire:model.live="filterAnnee" class="form-control">
                            <option value="">Toutes</option>
                            @for ($i = date('Y'); $i >= 1990; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Couleur</label>
                        <select wire:model.live="filterCouleur" class="form-control">
                            <option value="">Toutes</option>
                            @foreach ($couleurs as $couleur)
                            <option value="{{ $couleur }}">{{ $couleur }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times"></i> Effacer
                        </button>
                    </div>
                </div>
            </div>
        </div>

         <!-- Liste des véhicules -->
         <div class="card">
             <div class="card-header">
                 <h5 class="mb-0">Liste des véhicules ({{ $vehicules->total() }})</h5>
                 @if($search)
                     <small class="text-muted">Recherche: "{{ $search }}"</small>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
            <thead>
                            <tr>
                                <th>Photos</th>
                                <th>Marque/Modèle</th>
                                <th>Immatriculation</th>
                                <th>Type</th>
                                <th>Année</th>
                                <th>Statut</th>
                                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicules as $vehicule)
                                <tr>
                                    <td>
                                        @if ($vehicule->photos->count() > 0)
                                            <div class="d-flex">
                                                @foreach ($vehicule->photos->take(3) as $photo)
                                                    <img src="{{ $photo->url }}" class="img-thumbnail me-1"
                                                        style="width: 50px; height: 40px; object-fit: cover;">
                                                @endforeach
                                                @if ($vehicule->photos->count() > 3)
                                                    <span
                                                        class="badge bg-secondary align-self-center">+{{ $vehicule->photos->count() - 3 }}</span>
                                                @endif
                                            </div>
                        @else
                                            <span class="text-muted">Aucune photo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $vehicule->marque?->nom ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $vehicule->modele?->nom ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $vehicule->immatriculation }}</strong>
                                        @if ($vehicule->annee)
                                            <br><small class="text-muted">{{ $vehicule->annee }}</small>
                        @endif
                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $vehicule->type === 'propriete' ? 'primary' : 'info' }}">
                                            {{ $vehicule->type === 'propriete' ? 'Propriété' : 'Location' }}
                                        </span>
                                    </td>
                                    <td>{{ $vehicule->annee ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $vehicule->statut_badge }}">
                                            {{ ucfirst(str_replace('_', ' ', $vehicule->statut)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button wire:click="showDetails({{ $vehicule->id }})"
                                                class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i> Détails
                                            </button>
                                            <button wire:click="edit({{ $vehicule->id }})"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Modifier
                                            </button>
                                            <button wire:click="destroy({{ $vehicule->id }})"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ?')">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-car fa-2x mb-2"></i><br>
                                        Aucun véhicule trouvé.
                                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $vehicules->links() }}
        </div>
    </div>

    <!-- Modal de détails -->
    @if ($showDetailsModal && $selectedVehicule)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1"
            data-bs-backdrop="true" data-bs-keyboard="true" wire:click.self="closeDetailsModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-car"></i> Détails du véhicule - {{ $selectedVehicule->immatriculation }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeDetailsModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Informations générales -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">📋 Informations générales</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Immatriculation:</strong></td>
                                        <td>{{ $selectedVehicule->immatriculation }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Marque:</strong></td>
                                        <td>{{ $selectedVehicule->marque?->nom ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Modèle:</strong></td>
                                        <td>{{ $selectedVehicule->modele?->nom ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Type:</strong></td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $selectedVehicule->type === 'propriete' ? 'primary' : 'info' }}">
                                                {{ $selectedVehicule->type === 'propriete' ? 'Propriété' : 'Location' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Année:</strong></td>
                                        <td>{{ $selectedVehicule->annee ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Couleur:</strong></td>
                                        <td>{{ $selectedVehicule->couleur ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kilométrage:</strong></td>
                                        <td>{{ number_format($selectedVehicule->kilometrage ?? 0) }} km</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Statut:</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $selectedVehicule->statut_badge }}">
                                                {{ ucfirst(str_replace('_', ' ', $selectedVehicule->statut)) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Informations techniques -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">🔧 Informations techniques</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>N° Chassis:</strong></td>
                                        <td>{{ $selectedVehicule->numero_chassis ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>N° Moteur:</strong></td>
                                        <td>{{ $selectedVehicule->numero_moteur ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dernière révision:</strong></td>
                                        <td>{{ $selectedVehicule->derniere_revision ? $selectedVehicule->derniere_revision->format('d/m/Y') : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Prochaine révision:</strong></td>
                                        <td>{{ $selectedVehicule->prochaine_revision ? $selectedVehicule->prochaine_revision->format('d/m/Y') : '-' }}
                                        </td>
                                    </tr>
                                    @if ($selectedVehicule->type === 'propriete')
                                        <tr>
                                            <td><strong>Prix d'achat:</strong></td>
                                            <td>{{ $selectedVehicule->prix_achat ? number_format($selectedVehicule->prix_achat, 2) . ' €' : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date d'achat:</strong></td>
                                            <td>{{ $selectedVehicule->date_achat ? $selectedVehicule->date_achat->format('d/m/Y') : '-' }}
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td><strong>Prix location/jour:</strong></td>
                                            <td>{{ $selectedVehicule->prix_achat ? number_format($selectedVehicule->prix_achat, 2) . ' €' : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date de location:</strong></td>
                                            <td>{{ $selectedVehicule->date_achat ? $selectedVehicule->date_achat->format('d/m/Y') : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        @if ($selectedVehicule->description)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-primary mb-2">📝 Description</h6>
                                    <p class="text-muted">{{ $selectedVehicule->description }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Galerie de photos -->
                        @if ($selectedVehicule->photos->count() > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">📸 Photos du véhicule
                                        ({{ $selectedVehicule->photos->count() }})</h6>
                                    <div class="row">
                                        @foreach ($selectedVehicule->photos as $photo)
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    <img src="{{ $photo->url }}"
                                                        class="card-img-top photo-thumbnail"
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
                            </div>
                        @else
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Aucune photo disponible pour ce véhicule.
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="closeDetailsModal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal pour visualiser les photos en grand -->
    <div class="modal fade" id="photoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
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
</div>
