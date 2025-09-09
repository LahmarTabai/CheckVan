<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-car-crash me-2"></i>
                        Signalement de Dommages - {{ $affectation->vehicule->marque }}
                        {{ $affectation->vehicule->modele }}
                    </h4>
                    <button class="btn btn-primary" wire:click="toggleForm">
                        <i class="fas fa-plus me-1"></i>
                        Nouveau Dommage
                    </button>
                </div>

                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Interface 2D Tactile -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-mouse-pointer me-2"></i>
                                        Interface Tactile - Cliquez sur le véhicule pour marquer un dommage
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div id="vehicle-canvas-container"
                                        style="position: relative; background: #f8f9fa; min-height: 400px;">
                                        <canvas id="vehicle-canvas" width="100%" height="400"
                                            style="width: 100%; height: 400px; cursor: crosshair;">
                                        </canvas>

                                        @foreach ($dommages as $dommage)
                                            @if ($dommage->coord_x && $dommage->coord_y)
                                                <div class="damage-marker"
                                                    style="position: absolute;
                                                            left: {{ $dommage->coord_x }}%;
                                                            top: {{ $dommage->coord_y }}%;
                                                            transform: translate(-50%, -50%);
                                                            z-index: 10;">
                                                    <div class="damage-icon
                                                                @if ($dommage->severite == 'majeur') bg-danger
                                                                @elseif($dommage->severite == 'moyen') bg-warning
                                                                @else bg-info @endif
                                                                rounded-circle d-flex align-items-center justify-content-center text-white"
                                                        style="width: 30px; height: 30px; font-size: 12px;"
                                                        title="{{ ucfirst($dommage->type) }} - {{ ucfirst($dommage->severite) }}"
                                                        data-bs-toggle="tooltip">
                                                        @if ($dommage->type == 'rayure')
                                                            <i class="fas fa-cut"></i>
                                                        @elseif($dommage->type == 'bosse')
                                                            <i class="fas fa-circle"></i>
                                                        @elseif($dommage->type == 'choc')
                                                            <i class="fas fa-exclamation"></i>
                                                        @else
                                                            <i class="fas fa-question"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Légende des Dommages</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-danger rounded-circle me-2" style="width: 20px; height: 20px;">
                                        </div>
                                        <small>Majeur</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-warning rounded-circle me-2" style="width: 20px; height: 20px;">
                                        </div>
                                        <small>Moyen</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-info rounded-circle me-2" style="width: 20px; height: 20px;">
                                        </div>
                                        <small>Mineur</small>
                                    </div>

                                    <hr>

                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-cut text-primary me-2"></i>
                                        <small>Rayure</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-circle text-primary me-2"></i>
                                        <small>Bosse</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-exclamation text-primary me-2"></i>
                                        <small>Choc</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-question text-primary me-2"></i>
                                        <small>Autre</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de dommage -->
                    @if ($showForm)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    {{ $editingDommage ? 'Modifier le Dommage' : 'Nouveau Dommage' }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <form wire:submit.prevent="saveDommage">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Type de Dommage</label>
                                                <select class="form-select" wire:model="type">
                                                    <option value="rayure">Rayure</option>
                                                    <option value="bosse">Bosse</option>
                                                    <option value="choc">Choc</option>
                                                    <option value="autre">Autre</option>
                                                </select>
                                                @error('type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Sévérité</label>
                                                <select class="form-select" wire:model="severite">
                                                    <option value="mineur">Mineur</option>
                                                    <option value="moyen">Moyen</option>
                                                    <option value="majeur">Majeur</option>
                                                </select>
                                                @error('severite')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" wire:model="description" rows="3" placeholder="Décrivez le dommage observé..."></textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Photo du Dommage</label>
                                        <input type="file" class="form-control" wire:model="photo" accept="image/*">
                                        @error('photo')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @if ($photo)
                                            <div class="mt-2">
                                                <img src="{{ $photo->temporaryUrl() }}" class="img-thumbnail"
                                                    style="max-width: 200px;">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Position X (%)</label>
                                                <input type="number" class="form-control" wire:model="coord_x"
                                                    min="0" max="100" step="0.1"
                                                    placeholder="0-100">
                                                @error('coord_x')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Position Y (%)</label>
                                                <input type="number" class="form-control" wire:model="coord_y"
                                                    min="0" max="100" step="0.1"
                                                    placeholder="0-100">
                                                @error('coord_y')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Position Z (%)</label>
                                                <input type="number" class="form-control" wire:model="coord_z"
                                                    min="0" max="100" step="0.1"
                                                    placeholder="0-100">
                                                @error('coord_z')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save me-1"></i>
                                            {{ $editingDommage ? 'Modifier' : 'Enregistrer' }}
                                        </button>
                                        <button type="button" class="btn btn-secondary" wire:click="resetForm">
                                            <i class="fas fa-times me-1"></i>
                                            Annuler
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Liste des dommages existants -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Dommages Signalés</h5>
                        </div>
                        <div class="card-body">
                            @if ($dommages->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Sévérité</th>
                                                <th>Description</th>
                                                <th>Position</th>
                                                <th>Photo</th>
                                                <th>Statut</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dommages as $dommage)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-primary">
                                                            {{ ucfirst($dommage->type) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge
                                                            @if ($dommage->severite == 'majeur') bg-danger
                                                            @elseif($dommage->severite == 'moyen') bg-warning
                                                            @else bg-info @endif">
                                                            {{ ucfirst($dommage->severite) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small>{{ Str::limit($dommage->description, 50) }}</small>
                                                    </td>
                                                    <td>
                                                        @if ($dommage->coord_x && $dommage->coord_y)
                                                            <small>X: {{ $dommage->coord_x }}%<br>Y:
                                                                {{ $dommage->coord_y }}%</small>
                                                        @else
                                                            <small class="text-muted">Non défini</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($dommage->photo_path)
                                                            <img src="{{ Storage::url($dommage->photo_path) }}"
                                                                class="img-thumbnail"
                                                                style="width: 50px; height: 50px;">
                                                        @else
                                                            <small class="text-muted">Aucune</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($dommage->reparé)
                                                            <span class="badge bg-success">Réparé</span>
                                                        @else
                                                            <span class="badge bg-warning">En attente</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small>{{ $dommage->created_at->format('d/m/Y H:i') }}</small>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button class="btn btn-outline-primary"
                                                                wire:click="editDommage({{ $dommage->id }})"
                                                                title="Modifier">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            @if (!$dommage->reparé)
                                                                <button class="btn btn-outline-success"
                                                                    wire:click="markAsRepared({{ $dommage->id }})"
                                                                    title="Marquer comme réparé">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            @endif
                                                            <button class="btn btn-outline-danger"
                                                                wire:click="deleteDommage({{ $dommage->id }})"
                                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce dommage ?')"
                                                                title="Supprimer">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-car-crash fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun dommage signalé pour ce véhicule.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('vehicle-canvas');
            const ctx = canvas.getContext('2d');

            // Dessiner le véhicule (vue de côté simplifiée)
            function drawVehicle() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                // Couleur de fond
                ctx.fillStyle = '#e9ecef';
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Dessiner le véhicule (forme de van)
                ctx.fillStyle = '#6c757d';
                ctx.strokeStyle = '#495057';
                ctx.lineWidth = 2;

                // Corps principal du véhicule
                ctx.fillRect(50, 150, 300, 100);
                ctx.strokeRect(50, 150, 300, 100);

                // Cabine
                ctx.fillRect(50, 100, 120, 50);
                ctx.strokeRect(50, 100, 120, 50);

                // Roues
                ctx.fillStyle = '#343a40';
                ctx.beginPath();
                ctx.arc(100, 260, 25, 0, 2 * Math.PI);
                ctx.fill();
                ctx.stroke();

                ctx.beginPath();
                ctx.arc(300, 260, 25, 0, 2 * Math.PI);
                ctx.fill();
                ctx.stroke();

                // Fenêtres
                ctx.fillStyle = '#87ceeb';
                ctx.fillRect(60, 110, 100, 30);
                ctx.strokeRect(60, 110, 100, 30);

                // Porte
                ctx.strokeRect(200, 160, 60, 80);

                // Ajouter du texte
                ctx.fillStyle = '#495057';
                ctx.font = '14px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('Vue de côté du véhicule', canvas.width / 2, 30);
                ctx.fillText('Cliquez pour marquer un dommage', canvas.width / 2, 50);
            }

            // Gérer les clics sur le canvas
            canvas.addEventListener('click', function(event) {
                const rect = canvas.getBoundingClientRect();
                const x = event.clientX - rect.left;
                const y = event.clientY - rect.top;

                // Convertir en pourcentages
                const xPercent = (x / canvas.width) * 100;
                const yPercent = (y / canvas.height) * 100;

                // Mettre à jour les coordonnées dans Livewire
                @this.set('coord_x', xPercent.toFixed(1));
                @this.set('coord_y', yPercent.toFixed(1));

                // Dessiner un marqueur temporaire
                ctx.fillStyle = '#dc3545';
                ctx.beginPath();
                ctx.arc(x, y, 8, 0, 2 * Math.PI);
                ctx.fill();
                ctx.strokeStyle = '#fff';
                ctx.lineWidth = 2;
                ctx.stroke();

                // Afficher le formulaire si pas déjà ouvert
                if (!@this.showForm) {
                    @this.toggleForm();
                }
            });

            // Initialiser le dessin
            drawVehicle();

            // Redessiner quand la fenêtre change de taille
            window.addEventListener('resize', function() {
                drawVehicle();
            });
        });

        // Initialiser les tooltips Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
@endpush
