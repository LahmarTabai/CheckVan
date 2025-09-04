<div class="container py-4">
    <h2 class="mb-4">Tableau de bord</h2>

    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="card bg-light p-3">
                <h4>{{ $chauffeursCount }}</h4>
                <p>Chauffeurs</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light p-3">
                <h4>{{ $vehiculesCount }}</h4>
                <p>Véhicules</p>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-light p-3">
                <h5>{{ $tachesEnAttente }}</h5>
                <p>En attente</p>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-light p-3">
                <h5>{{ $tachesEnCours }}</h5>
                <p>En cours</p>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-light p-3">
                <h5>{{ $tachesTerminees }}</h5>
                <p>Terminées</p>
            </div>
        </div>
    </div>

    <h5 class="mb-3">Dernières tâches</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Chauffeur</th>
                <th>Véhicule</th>
                <th>Date</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dernieresTaches as $tache)
            <tr>
                <td>{{ $tache->chauffeur->name }}</td>
                <td>{{ $tache->vehicule->immatriculation }}</td>
                <td>{{ $tache->start_date->format('d/m/Y H:i') }}</td>
                <td>
                    <span class="badge bg-{{
                            $tache->status === 'en_attente' ? 'warning' : (
                            $tache->status === 'en_cours' ? 'primary' : 'success')
                        }}">
                        {{ ucfirst($tache->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">Aucune tâche récente.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
