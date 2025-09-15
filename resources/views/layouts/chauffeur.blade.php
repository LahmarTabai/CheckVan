<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Chauffeur - CheckVan 2050</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    {{-- Style 2050 --}}
    <link href="{{ asset('css/checkvan-2050.css') }}" rel="stylesheet">

    @livewireStyles
</head>

<body class="body-2050">
    <div class="d-flex">
        {{-- Sidebar Futuriste --}}
        <div class="sidebar-2050">
            <div class="sidebar-header-2050">
                <div class="logo-2050">
                    <i class="fas fa-truck-fast"></i>
                    <span>CheckVan</span>
                    <small>2050</small>
                </div>
            </div>

            <div class="sidebar-content-2050">
                <div class="user-info-2050">
                    <div class="user-avatar-2050">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-details-2050">
                        <h6>{{ Auth::user()->nom ?? 'Chauffeur' }} {{ Auth::user()->prenom ?? '' }}</h6>
                        <small class="text-muted">Chauffeur</small>
                    </div>
                </div>

                <nav class="nav-2050">
                    <a href="{{ route('chauffeur.dashboard') }}"
                        class="nav-item-2050 {{ request()->routeIs('chauffeur.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('chauffeur.prise-en-charge') }}"
                        class="nav-item-2050 {{ request()->routeIs('chauffeur.prise-en-charge') ? 'active' : '' }}">
                        <i class="fas fa-car"></i>
                        <span>Prise en charge</span>
                    </a>
                    <a href="{{ route('chauffeur.taches') }}"
                        class="nav-item-2050 {{ request()->routeIs('chauffeur.taches') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Mes Tâches</span>
                    </a>
                    <a href="{{ route('chauffeur.dommages', 0) }}"
                        class="nav-item-2050 {{ request()->routeIs('chauffeur.dommages') ? 'active' : '' }}">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Dommages</span>
                    </a>
                </nav>
            </div>

            <div class="sidebar-footer-2050">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-2050 w-100">
                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                    </button>
                </form>
            </div>
        </div>

        {{-- Contenu Principal --}}
        <div class="main-content-2050">
            {{-- Header --}}
            <header class="header-2050">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-gradient">Interface Chauffeur</h4>
                        <small class="text-muted">Gestion des tâches et véhicules</small>
                    </div>
                    <div class="header-actions-2050">
                        <div class="status-indicator-2050">
                            <i class="fas fa-circle text-success"></i>
                            <span>En ligne</span>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Contenu --}}
            <main class="content-2050">
                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>

</html>
