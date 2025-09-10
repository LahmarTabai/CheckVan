<div class="container py-4">
    <h3>Prise en charge du véhicule</h3>


    @if ($message)
        <div class="alert alert-success">{{ $message }}</div>
    @endif

    <form wire:submit.prevent="prendreEnCharge">
        <div class="mb-3">
            <label for="vehicule_id" class="form-label">Sélectionner un véhicule</label>
            <select wire:model="vehicule_id" class="form-select">
                <option value="">-- Choisir --</option>
                @foreach ($vehicules as $vehicule)
                    <option value="{{ $vehicule->id }}">{{ $vehicule->marque }} - {{ $vehicule->immatriculation }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Photo avant utilisation (facultatif)</label>
            <input type="file" wire:model="photo_avant" class="form-control">
            @error('photo_avant')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Prendre en charge</button>
    </form>

    @if ($affectation)
        <hr>
        <h5 class="mt-4">Véhicule actuellement pris en charge</h5>
        <p><strong>Véhicule :</strong> {{ $affectation->vehicule->marque }} -
            {{ $affectation->vehicule->immatriculation }}
        </p>

        <!-- Bouton pour signaler des dommages -->
        <div class="mb-3">
            <a href="{{ route('chauffeur.dommages', $affectation->id) }}" class="btn btn-warning">
                <i class="fas fa-car-crash me-1"></i>
                Signaler des Dommages
            </a>
        </div>

        <form wire:submit.prevent="rendreVehicule" class="mt-3">
            <label class="form-label">Photo après utilisation (facultatif)</label>
            <input type="file" wire:model="photo_apres" class="form-control mb-2">
            <button type="submit" class="btn btn-danger">Rendre le véhicule</button>
        </form>
    @endif
</div>
