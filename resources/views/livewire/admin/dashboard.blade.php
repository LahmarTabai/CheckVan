<div>
    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            <div class="glass-effect rounded-circle p-3">
                <i class="fas fa-chart-line text-gradient fs-4"></i>
            </div>
        </div>
        <div>
            <h1 class="text-gradient mb-0">Tableau de Bord</h1>
            <p class="text-muted mb-0">Vue d'ensemble de votre flotte 2050</p>
        </div>
    </div>

    <!-- Statistiques Principales -->
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="card-2050 hover-lift">
                <div class="card-body-2050 text-center p-4">
                    <div class="stat-icon-2050 mb-3">
                        <i class="fas fa-users text-primary"></i>
                    </div>
                    <h3 class="stat-number-2050 mb-1">{{ $chauffeursCount }}</h3>
                    <p class="stat-label-2050 mb-0">Chauffeurs Actifs</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card-2050 hover-lift">
                <div class="card-body-2050 text-center p-4">
                    <div class="stat-icon-2050 mb-3">
                        <i class="fas fa-car text-success"></i>
                    </div>
                    <h3 class="stat-number-2050 mb-1">{{ $vehiculesCount }}</h3>
                    <p class="stat-label-2050 mb-0">Véhicules en Flotte</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card-2050 hover-lift">
                <div class="card-body-2050 text-center p-4">
                    <div class="stat-icon-2050 mb-3">
                        <i class="fas fa-link text-warning"></i>
                    </div>
                    <h3 class="stat-number-2050 mb-1">{{ $affectationsActives }}</h3>
                    <p class="stat-label-2050 mb-0">Affectations Actives</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card-2050 hover-lift">
                <div class="card-body-2050 text-center p-4">
                    <div class="stat-icon-2050 mb-3">
                        <i class="fas fa-tasks text-info"></i>
                    </div>
                    <h3 class="stat-number-2050 mb-1">{{ $tachesEnCours }}</h3>
                    <p class="stat-label-2050 mb-0">Tâches en Cours</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques des Tâches -->
    <div class="row g-4 mb-5">
        <div class="col-lg-4 col-md-4">
            <div class="card-2050 hover-lift">
                <div class="card-body-2050 text-center p-4">
                    <div class="stat-icon-2050 mb-3">
                        <i class="fas fa-clock text-warning"></i>
                    </div>
                    <h4 class="stat-number-2050 mb-1">{{ $tachesEnAttente }}</h4>
                    <p class="stat-label-2050 mb-0">En Attente</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="card-2050 hover-lift">
                <div class="card-body-2050 text-center p-4">
                    <div class="stat-icon-2050 mb-3">
                        <i class="fas fa-play text-primary"></i>
                    </div>
                    <h4 class="stat-number-2050 mb-1">{{ $tachesEnCours }}</h4>
                    <p class="stat-label-2050 mb-0">En Cours</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="card-2050 hover-lift">
                <div class="card-body-2050 text-center p-4">
                    <div class="stat-icon-2050 mb-3">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <h4 class="stat-number-2050 mb-1">{{ $tachesTerminees }}</h4>
                    <p class="stat-label-2050 mb-0">Terminées</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernières Tâches -->
    <div class="card-2050 hover-lift">
        <div class="card-header-2050">
            <h6 class="mb-0">
                <i class="fas fa-history me-2"></i>Dernières Tâches
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-2050 mb-0">
                    <thead>
                        <tr>
                            <th class="text-primary"><i class="fas fa-user me-2"></i>Chauffeur</th>
                            <th class="text-primary"><i class="fas fa-car me-2"></i>Véhicule</th>
                            <th class="text-primary"><i class="fas fa-calendar me-2"></i>Date</th>
                            <th class="text-primary"><i class="fas fa-info-circle me-2"></i>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dernieresTaches as $tache)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm-2050 me-2">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $tache->chauffeur->nom ?? 'N/A' }}
                                                {{ $tache->chauffeur->prenom ?? '' }}</div>
                                            <small class="text-muted">{{ $tache->chauffeur->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-medium">{{ $tache->vehicule->immatriculation ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $tache->vehicule->marque->nom ?? 'N/A' }}
                                            {{ $tache->vehicule->modele->nom ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-medium">
                                            {{ $tache->start_date ? $tache->start_date->format('d/m/Y') : 'N/A' }}
                                        </div>
                                        <small
                                            class="text-muted">{{ $tache->start_date ? $tache->start_date->format('H:i') : '' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge badge-{{ $tache->status === 'en_attente' ? 'warning' : ($tache->status === 'en_cours' ? 'primary' : 'success') }}-2050">
                                        <i
                                            class="fas fa-{{ $tache->status === 'en_attente' ? 'clock' : ($tache->status === 'en_cours' ? 'play' : 'check') }} me-1"></i>
                                        {{ ucfirst($tache->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="empty-state-2050">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Aucune tâche récente</h5>
                                        <p class="text-muted">Les nouvelles tâches apparaîtront ici</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
