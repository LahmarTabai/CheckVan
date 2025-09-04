<div class="container py-4">
    <h3>Détails de la Tâche #{{ $tache->id }}</h3>

    <div class="mb-3">
        <strong>Véhicule :</strong>
        {{ $tache->vehicule->marque ?? '-' }} - {{ $tache->vehicule->immatriculation ?? '-' }}
    </div>

    <div class="mb-3">
        <strong>Statut :</strong>
        <span
            class="badge bg-{{ $tache->status == 'en_cours' ? 'warning' : ($tache->status == 'terminée' ? 'success' : 'secondary') }}">
            {{ ucfirst($tache->status) }}
        </span>
    </div>

    <div class="mb-3">
        <strong>Date de début :</strong> {{ $tache->start_date ?? '-' }}<br>
        <strong>Date de fin :</strong> {{ $tache->end_date ?? '-' }}
    </div>

    <div class="mb-3">
        <strong>Photos associées :</strong><br>
        @forelse($tache->photos as $photo)
        <div class="mb-2">
            <strong>{{ ucfirst($photo->type) }}</strong><br>
            <img src="{{ Storage::url($photo->path) }}" class="img-fluid rounded" style="max-width: 300px;">
        </div>
        @empty
        <p>Aucune photo enregistrée.</p>
        @endforelse
    </div>

    <a href="{{ route('chauffeur.taches') }}" class="btn btn-secondary mt-3">⬅ Retour</a>
</div>
