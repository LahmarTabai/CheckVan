<div>
    <!-- En-tête -->
    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            <div class="glass-effect rounded-circle p-3">
                <i class="fas fa-car-crash text-gradient fs-4"></i>
            </div>
        </div>
        <div>
            <h1 class="text-gradient mb-0">Signalement de Dommages</h1>
            <p class="text-muted mb-0">
                @if ($affectation)
                    {{ $affectation->vehicule->marque->nom ?? 'N/A' }}
                    {{ $affectation->vehicule->modele->nom ?? 'N/A' }}
                @else
                    Interface de signalement 2050
                @endif
            </p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success-2050 alert-dismissible fade show animate-fade-in-up" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger-2050 alert-dismissible fade show animate-fade-in-up" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (!$affectation)
        <!-- Aucun véhicule assigné -->
        <div class="card-2050 hover-lift">
            <div class="card-body text-center py-5">
                <div class="glass-effect rounded-circle p-4 mx-auto mb-4" style="width: 120px; height: 120px;">
                    <i class="fas fa-car-crash text-warning fs-1"></i>
                </div>
                <h3 class="text-gradient mb-3">Aucun véhicule assigné</h3>
                <p class="text-muted mb-4">Vous devez d'abord prendre un véhicule en charge pour pouvoir signaler des
                    dommages.</p>
                <a href="{{ route('chauffeur.prise-en-charge') }}" class="btn btn-primary-2050">
                    <i class="fas fa-hand-paper me-2"></i>Prendre un véhicule en charge
                </a>
            </div>
        </div>
    @else
        <!-- Interface de signalement -->
        <div class="row g-4">
            <!-- Interface 2D Tactile -->
            <div class="col-lg-8">
                <div class="card-2050 hover-lift">
                    <div class="card-header-2050">
                        <h6 class="mb-0">
                            <i class="fas fa-mouse-pointer me-2"></i>Interface Tactile 2050
                        </h6>
                        <small class="text-muted">Cliquez sur le véhicule pour marquer un dommage</small>
                    </div>
                    <div class="card-body p-4">
                        <div class="position-relative">
                            <canvas id="vehicleCanvas" width="600" height="400" class="border rounded glass-effect"
                                style="cursor: crosshair; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                            </canvas>

                            <!-- Légende des zones -->
                            <div class="mt-3">
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="glass-effect rounded-circle p-2 me-2"
                                                style="width: 20px; height: 20px; background: #ff6b6b;"></div>
                                            <small class="text-muted">Avant</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="glass-effect rounded-circle p-2 me-2"
                                                style="width: 20px; height: 20px; background: #4ecdc4;"></div>
                                            <small class="text-muted">Arrière</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="glass-effect rounded-circle p-2 me-2"
                                                style="width: 20px; height: 20px; background: #45b7d1;"></div>
                                            <small class="text-muted">Côté Gauche</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="glass-effect rounded-circle p-2 me-2"
                                                style="width: 20px; height: 20px; background: #f9ca24;"></div>
                                            <small class="text-muted">Côté Droit</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire et liste des dommages -->
            <div class="col-lg-4">
                <!-- Formulaire de dommage -->
                @if ($showForm)
                    <div class="card-2050 hover-lift mb-4">
                        <div class="card-header-2050">
                            <h6 class="mb-0">
                                <i class="fas fa-{{ $editingDommage ? 'edit' : 'plus' }} me-2"></i>
                                {{ $editingDommage ? 'Modifier le dommage' : 'Nouveau dommage' }}
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <form wire:submit.prevent="saveDommage">
                                <div class="mb-3">
                                    <label class="form-label-2050">Type de dommage <span
                                            class="text-danger">*</span></label>
                                    <select wire:model="type" class="form-control-2050">
                                        <option value="rayure">Rayure</option>
                                        <option value="bosse">Bosse</option>
                                        <option value="choc">Choc</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                    @error('type')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-2050">Sévérité <span class="text-danger">*</span></label>
                                    <select wire:model="severite" class="form-control-2050">
                                        <option value="mineur">Mineur</option>
                                        <option value="moyen">Moyen</option>
                                        <option value="majeur">Majeur</option>
                                    </select>
                                    @error('severite')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-2050">Description</label>
                                    <textarea wire:model="description" class="form-control-2050" rows="3" placeholder="Décrivez le dommage..."></textarea>
                                    @error('description')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-2050">Photo du dommage</label>
                                    <input type="file" wire:model="photo" class="form-control-2050"
                                        accept="image/*">
                                    <small class="text-muted">Formats acceptés : JPG, PNG, GIF. Taille max :
                                        2MB.</small>
                                    @error('photo')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Coordonnées sur le véhicule -->
                                <div class="row g-3 mb-3">
                                    <div class="col-4">
                                        <label class="form-label-2050">X (%)</label>
                                        <input type="number" wire:model="coord_x" class="form-control-2050"
                                            min="0" max="100" placeholder="0">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label-2050">Y (%)</label>
                                        <input type="number" wire:model="coord_y" class="form-control-2050"
                                            min="0" max="100" placeholder="0">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label-2050">Z (%)</label>
                                        <input type="number" wire:model="coord_z" class="form-control-2050"
                                            min="0" max="100" placeholder="0">
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary-2050 flex-fill">
                                        <i class="fas fa-save me-2"></i>
                                        {{ $editingDommage ? 'Modifier' : 'Enregistrer' }}
                                    </button>
                                    <button type="button" wire:click="toggleForm" class="btn btn-outline-2050">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Liste des dommages -->
                <div class="card-2050 hover-lift">
                    <div class="card-header-2050">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-list me-2"></i>Dommages signalés
                            </h6>
                            @if (!$showForm)
                                <button wire:click="toggleForm" class="btn btn-primary-2050 btn-sm">
                                    <i class="fas fa-plus me-1"></i>Nouveau
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @forelse($dommages as $dommage)
                            <div class="border-bottom p-3 {{ $loop->last ? 'border-0' : '' }}">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            @php
                                                $severiteClasses = [
                                                    'mineur' => 'badge-success-2050',
                                                    'moyen' => 'badge-warning-2050',
                                                    'majeur' => 'badge-danger-2050',
                                                ];
                                                $severiteIcons = [
                                                    'mineur' => 'info-circle',
                                                    'moyen' => 'exclamation-triangle',
                                                    'majeur' => 'exclamation-circle',
                                                ];
                                            @endphp
                                            <span
                                                class="badge {{ $severiteClasses[$dommage->severite] ?? 'badge-secondary-2050' }} me-2">
                                                <i
                                                    class="fas fa-{{ $severiteIcons[$dommage->severite] ?? 'question' }} me-1"></i>
                                                {{ ucfirst($dommage->severite) }}
                                            </span>
                                            <span class="badge badge-info-2050">
                                                {{ ucfirst($dommage->type) }}
                                            </span>
                                        </div>

                                        @if ($dommage->description)
                                            <p class="text-muted mb-2 small">{{ $dommage->description }}</p>
                                        @endif

                                        <div class="d-flex align-items-center text-muted small">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            <span>X: {{ $dommage->coord_x ?? 0 }}% | Y: {{ $dommage->coord_y ?? 0 }}%
                                                | Z: {{ $dommage->coord_z ?? 0 }}%</span>
                                        </div>

                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $dommage->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>

                                    <div class="btn-group-actions">
                                        <button wire:click="editDommage({{ $dommage->id }})"
                                            class="btn btn-warning-2050 btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="deleteDommage({{ $dommage->id }})"
                                            class="btn btn-danger-2050 btn-sm"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce dommage ?')"
                                            title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                @if ($dommage->photo_path)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($dommage->photo_path) }}" alt="Photo du dommage"
                                            class="img-fluid rounded" style="max-height: 100px; cursor: pointer;"
                                            data-bs-toggle="modal" data-bs-target="#photoModal{{ $dommage->id }}">
                                    </div>

                                    <!-- Modal pour afficher la photo en grand -->
                                    <div class="modal fade" id="photoModal{{ $dommage->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Photo du dommage</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ Storage::url($dommage->photo_path) }}"
                                                        alt="Photo du dommage" class="img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <div class="glass-effect rounded-circle p-4 mx-auto mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="fas fa-shield-alt text-muted fs-2"></i>
                                </div>
                                <h5 class="text-muted mb-3">Aucun dommage</h5>
                                <p class="text-muted">Aucun dommage n'a été signalé sur ce véhicule.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('vehicleCanvas');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const dommages = @json($dommages ?? []);

            // Dessiner le véhicule
            function drawVehicle() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                // Gradient de fond
                const gradient = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
                gradient.addColorStop(0, '#667eea');
                gradient.addColorStop(1, '#764ba2');
                ctx.fillStyle = gradient;
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Dessiner le véhicule (vue de côté)
                ctx.fillStyle = 'rgba(255, 255, 255, 0.1)';
                ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
                ctx.lineWidth = 2;

                // Corps du véhicule
                ctx.beginPath();
                ctx.roundRect(100, 150, 400, 100, 20);
                ctx.fill();
                ctx.stroke();

                // Cabine
                ctx.beginPath();
                ctx.roundRect(120, 120, 120, 80, 15);
                ctx.fill();
                ctx.stroke();

                // Roues
                ctx.fillStyle = 'rgba(0, 0, 0, 0.3)';
                ctx.beginPath();
                ctx.arc(150, 270, 25, 0, 2 * Math.PI);
                ctx.fill();
                ctx.beginPath();
                ctx.arc(450, 270, 25, 0, 2 * Math.PI);
                ctx.fill();

                // Dessiner les dommages existants
                dommages.forEach(dommage => {
                    if (dommage.coord_x && dommage.coord_y) {
                        const x = (dommage.coord_x / 100) * canvas.width;
                        const y = (dommage.coord_y / 100) * canvas.height;

                        ctx.fillStyle = getDommageColor(dommage.severite);
                        ctx.beginPath();
                        ctx.arc(x, y, 8, 0, 2 * Math.PI);
                        ctx.fill();
                        ctx.strokeStyle = '#fff';
                        ctx.lineWidth = 2;
                        ctx.stroke();
                    }
                });
            }

            function getDommageColor(severite) {
                switch (severite) {
                    case 'mineur':
                        return '#28a745';
                    case 'moyen':
                        return '#ffc107';
                    case 'majeur':
                        return '#dc3545';
                    default:
                        return '#6c757d';
                }
            }

            // Gérer les clics sur le canvas
            canvas.addEventListener('click', function(e) {
                const rect = canvas.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;

                // Mettre à jour les coordonnées dans le formulaire
                @this.set('coord_x', Math.round(x));
                @this.set('coord_y', Math.round(y));

                // Dessiner un point temporaire
                ctx.fillStyle = '#ff6b6b';
                ctx.beginPath();
                ctx.arc((x / 100) * canvas.width, (y / 100) * canvas.height, 8, 0, 2 * Math.PI);
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

            // Redessiner quand les dommages changent
            Livewire.on('dommageUpdated', () => {
                drawVehicle();
            });
        });

        // Polyfill pour roundRect si pas supporté
        if (!CanvasRenderingContext2D.prototype.roundRect) {
            CanvasRenderingContext2D.prototype.roundRect = function(x, y, width, height, radius) {
                this.beginPath();
                this.moveTo(x + radius, y);
                this.lineTo(x + width - radius, y);
                this.quadraticCurveTo(x + width, y, x + width, y + radius);
                this.lineTo(x + width, y + height - radius);
                this.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
                this.lineTo(x + radius, y + height);
                this.quadraticCurveTo(x, y + height, x, y + height - radius);
                this.lineTo(x, y + radius);
                this.quadraticCurveTo(x, y, x + radius, y);
                this.closePath();
            };
        }
    </script>
@endpush
