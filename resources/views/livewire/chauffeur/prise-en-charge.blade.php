<div>
    <!-- En-tête -->
    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            <div class="glass-effect rounded-circle p-3">
                <i class="fas fa-hand-paper text-gradient fs-4"></i>
            </div>
        </div>
        <div>
            <h1 class="text-gradient mb-0">Prise en Charge</h1>
            <p class="text-muted mb-0">Sélectionnez un véhicule à prendre en charge</p>
        </div>
    </div>

    @if ($message)
        <div class="alert alert-success-2050 alert-dismissible fade show animate-fade-in-up" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Formulaire de prise en charge -->
        <div class="col-lg-8">
            <div class="card-2050 hover-lift">
                <div class="card-header-2050">
                    <h6 class="mb-0">
                        <i class="fas fa-car me-2"></i>Sélection du Véhicule
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="prendreEnCharge">
                        <div class="form-section-2050">
                            <h6 class="section-title-2050">
                                <i class="fas fa-car me-2"></i>Sélection du véhicule
                            </h6>

                            <div class="form-row-2050">
                                <div class="form-col-2050 col-12">
                                    <div class="form-group-2050">
                                        <label class="form-label-2050">
                                            Véhicule disponible <span class="required">*</span>
                                        </label>
                                        <select wire:model="vehicule_id" class="form-control-2050 select2-2050">
                                            <option value="">-- Choisir un véhicule --</option>
                                            @foreach ($vehicules as $vehicule)
                                                <option value="{{ $vehicule->id }}">
                                                    {{ $vehicule->immatriculation }} -
                                                    {{ $vehicule->marque->nom ?? 'N/A' }}
                                                    {{ $vehicule->modele->nom ?? 'N/A' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-help-2050">Sélectionnez le véhicule que vous souhaitez
                                            prendre en charge</small>
                                        @error('vehicule_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions-2050">
                            <button type="submit" class="btn btn-primary-2050" {{ !$vehicule_id ? 'disabled' : '' }}>
                                <i class="fas fa-hand-paper me-2"></i>Prendre en Charge
                            </button>
                            <a href="{{ route('chauffeur.dashboard') }}" class="btn btn-outline-2050">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informations sur les véhicules -->
        <div class="col-lg-4">
            <div class="card-2050 hover-lift">
                <div class="card-header-2050">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <h6 class="text-gradient">Véhicules disponibles</h6>
                        <p class="text-muted mb-0">{{ $vehicules->count() }} véhicule(s) disponible(s)</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-gradient">Instructions</h6>
                        <ul class="list-unstyled text-muted small">
                            <li><i class="fas fa-check text-success me-2"></i>Sélectionnez un véhicule</li>
                            <li><i class="fas fa-check text-success me-2"></i>Cliquez sur "Prendre en Charge"</li>
                            <li><i class="fas fa-check text-success me-2"></i>Vous pourrez ensuite signaler des dommages
                            </li>
                        </ul>
                    </div>

                    @if ($vehicule_id)
                        @php
                            $selectedVehicule = $vehicules->firstWhere('id', $vehicule_id);
                        @endphp
                        @if ($selectedVehicule)
                            <div class="glass-effect p-3 rounded">
                                <h6 class="text-gradient mb-2">Véhicule sélectionné</h6>
                                <div class="row g-2 small">
                                    <div class="col-6">
                                        <strong>Immatriculation:</strong><br>
                                        <span class="text-muted">{{ $selectedVehicule->immatriculation }}</span>
                                    </div>
                                    <div class="col-6">
                                        <strong>Marque/Modèle:</strong><br>
                                        <span class="text-muted">{{ $selectedVehicule->marque->nom ?? 'N/A' }}
                                            {{ $selectedVehicule->modele->nom ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-6">
                                        <strong>Type:</strong><br>
                                        <span class="text-muted">{{ ucfirst($selectedVehicule->type) }}</span>
                                    </div>
                                    <div class="col-6">
                                        <strong>Année:</strong><br>
                                        <span class="text-muted">{{ $selectedVehicule->annee }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des véhicules disponibles -->
    @if ($vehicules->count() > 0)
        <div class="mt-4">
            <div class="card-2050 hover-lift">
                <div class="card-header-2050">
                    <h6 class="mb-0">
                        <i class="fas fa-list me-2"></i>Véhicules Disponibles
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        @foreach ($vehicules as $vehicule)
                            <div
                                class="col-md-6 border-end {{ $loop->odd ? 'border-bottom' : '' }} {{ $loop->even ? 'border-bottom' : '' }}">
                                <div class="p-4 {{ $vehicule_id == $vehicule->id ? 'bg-primary-2050-light' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <div class="glass-effect rounded-circle p-3 me-3">
                                            <i class="fas fa-car text-gradient"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $vehicule->immatriculation }}</h6>
                                            <p class="text-muted mb-1">
                                                {{ $vehicule->marque->nom ?? 'N/A' }}
                                                {{ $vehicule->modele->nom ?? 'N/A' }}
                                            </p>
                                            <div class="d-flex gap-2">
                                                <span
                                                    class="badge badge-info-2050">{{ ucfirst($vehicule->type) }}</span>
                                                <span class="badge badge-secondary-2050">{{ $vehicule->annee }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <button wire:click="$set('vehicule_id', {{ $vehicule->id }})"
                                                class="btn btn-sm {{ $vehicule_id == $vehicule->id ? 'btn-primary-2050' : 'btn-outline-2050' }}">
                                                <i
                                                    class="fas fa-{{ $vehicule_id == $vehicule->id ? 'check' : 'hand-paper' }}"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="mt-4">
            <div class="card-2050 hover-lift">
                <div class="card-body text-center py-5">
                    <div class="glass-effect rounded-circle p-4 mx-auto mb-3" style="width: 100px; height: 100px;">
                        <i class="fas fa-car text-muted fs-1"></i>
                    </div>
                    <h4 class="text-gradient mb-3">Aucun véhicule disponible</h4>
                    <p class="text-muted">Tous les véhicules sont actuellement pris en charge par d'autres chauffeurs.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
