<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Admin - CheckVan 2050</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/checkvan-2050.css') }}" rel="stylesheet">
    @livewireStyles
</head>

<body>
    <div class="d-flex">
        {{-- Sidebar Futuriste --}}
        <div class="sidebar-2050 p-3 vh-100" style="width: 280px;">
            <div class="text-center mb-4">
                <h3 class="text-gradient mb-0">🚐 CheckVan</h3>
                <small class="text-muted">2050 Edition</small>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home me-2"></i>Tableau de bord
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.vehicules') }}"
                        class="nav-link {{ request()->routeIs('admin.vehicules') ? 'active' : '' }}">
                        <i class="fas fa-car me-2"></i>Véhicules
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.chauffeurs') }}"
                        class="nav-link {{ request()->routeIs('admin.chauffeurs') ? 'active' : '' }}">
                        <i class="fas fa-user-tie me-2"></i>Chauffeurs
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.affectations') }}"
                        class="nav-link {{ request()->routeIs('admin.affectations') ? 'active' : '' }}">
                        <i class="fas fa-link me-2"></i>Affectations
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.taches') }}"
                        class="nav-link {{ request()->routeIs('admin.taches') ? 'active' : '' }}">
                        <i class="fas fa-tasks me-2"></i>Tâches
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.dommages') }}"
                        class="nav-link {{ request()->routeIs('admin.dommages') ? 'active' : '' }}">
                        <i class="fas fa-exclamation-triangle me-2"></i>Dommages
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.map') }}"
                        class="nav-link {{ request()->routeIs('admin.map') ? 'active' : '' }}">
                        <i class="fas fa-map me-2"></i>Carte
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.notifications') }}"
                        class="nav-link {{ request()->routeIs('admin.notifications') ? 'active' : '' }}">
                        <i class="fas fa-bell me-2"></i>Notifications
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-2050 w-100">
                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        {{-- Contenu principal --}}
        <div class="main-content animate-fade-in-up">
            {{ $slot }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>

</html>
