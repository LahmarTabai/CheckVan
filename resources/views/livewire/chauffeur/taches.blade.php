<div class="container py-4">
    <h3>Mes Tâches</h3>

    @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Véhicule</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($taches as $tache)
            <tr>
                <td>{{ $tache->id }}</td>
                <td>{{ $tache->vehicule->marque ?? '-' }} - {{ $tache->vehicule->immatriculation ?? '-' }}</td>
                <td>{{ $tache->start_date ?? '-' }}</td>
                <td>{{ $tache->end_date ?? '-' }}</td>
                <td>
                    <span
                        class="badge bg-{{ $tache->status == 'en_cours' ? 'warning' : ($tache->status == 'terminée' ? 'success' : 'secondary') }}">
                        {{ ucfirst($tache->status) }}
                    </span>
                </td>
                <td>
                    @if($tache->status === 'en_attente')
                    <button class="btn btn-sm btn-primary"
                        wire:click="commencerTache({{ $tache->id }})">Commencer</button>
                    @elseif($tache->status === 'en_cours')
                    <button class="btn btn-sm btn-success"
                        wire:click="terminerTache({{ $tache->id }})">Terminer</button>
                    @else
                    <em>-</em>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Aucune tâche pour le moment.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
