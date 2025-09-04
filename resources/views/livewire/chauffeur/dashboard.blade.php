<div class="container py-4">
    <h2 class="mb-4">Tableau de bord Chauffeur</h2>

    <div class="row">
        <!-- Véhicule affecté -->
        <div class="col-md-6">
            <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white">Véhicule Actuel</div>
                <div class="card-body">
                    @if($vehiculeAffecte)
                    <p><strong>Marque :</strong> {{ $vehiculeAffecte->vehicule->marque }}</p>
                    <p><strong>Modèle :</strong> {{ $vehiculeAffecte->vehicule->modele }}</p>
                    <p><strong>Immatriculation :</strong> {{ $vehiculeAffecte->vehicule->immatriculation }}</p>
                    @if($vehiculeAffecte->vehicule->photo)
                    <img src="{{ Storage::url($vehiculeAffecte->vehicule->photo) }}" alt="photo véhicule"
                        class="img-fluid mt-2" width="200">
                    @endif
                    @else
                    <p>Aucun véhicule pris en charge actuellement.</p>
                    <a href="{{ route('chauffeur.prise-en-charge') }}" class="btn btn-sm btn-outline-primary">Prendre un
                        véhicule</a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tâches récentes -->
        <div class="col-md-6">
            <div class="card border-secondary mb-3">
                <div class="card-header bg-secondary text-white">Dernières Tâches</div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($tachesRecentes as $tache)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $tache->start_date }}
                            <span class="badge bg-info text-dark text-uppercase">{{ $tache->status }}</span>
                        </li>
                        @empty
                        <li class="list-group-item">Aucune tâche trouvée.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
