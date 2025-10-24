<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Chauffeur - CheckVan 2050</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    {{-- Style 2050 --}}
    <link href="{{ asset('css/checkvan-2050.css') }}" rel="stylesheet">
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

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
                <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                    @csrf
                    <button type="submit" class="btn btn-outline-2050 w-100" onclick="handleLogout(event)">
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
    {{-- jQuery (requis pour Select2) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- Select2 2050 Initialization --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 with 2050 theme
            $('.select2-2050').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Sélectionner...',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "Aucun résultat trouvé";
                    },
                    searching: function() {
                        return "Recherche en cours...";
                    }
                }
            });

            // Custom styling for 2050 theme
            $('.select2-2050').on('select2:open', function() {
                $('.select2-dropdown').addClass('select2-dropdown-2050');
            });

            // Reinitialize Select2 after Livewire updates
            document.addEventListener('livewire:navigated', function() {
                $('.select2-2050').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Sélectionner...',
                    allowClear: true,
                    language: {
                        noResults: function() {
                            return "Aucun résultat trouvé";
                        },
                        searching: function() {
                            return "Recherche en cours...";
                        }
                    }
                });
            });

            // Reinitialize Select2 after Livewire component updates
            document.addEventListener('livewire:updated', function() {
                $('.select2-2050').select2('destroy').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Sélectionner...',
                    allowClear: true,
                    language: {
                        noResults: function() {
                            return "Aucun résultat trouvé";
                        },
                        searching: function() {
                            return "Recherche en cours...";
                        }
                    }
                });
            });
        });
    </script>

    {{-- Heartbeat Service pour maintenir le statut online --}}
    <script src="{{ asset('js/heartbeat.js') }}"></script>
    <script>
        // Activer l'inclusion du GPS dans le heartbeat pour les chauffeurs
        window.includeGPSInHeartbeat = true;

        // ✅ CORRECTION : Stopper le heartbeat avant la déconnexion
        function handleLogout(event) {
            event.preventDefault(); // Empêcher la soumission immédiate

            console.log('🔐 Déconnexion en cours...');

            // Arrêter le heartbeat et marquer comme offline
            if (window.heartbeatService) {
                window.heartbeatService.stop();
            }

            // Attendre 500ms pour que la requête offline soit envoyée
            setTimeout(() => {
                document.getElementById('logoutForm').submit();
            }, 500);
        }
    </script>

    @livewireScripts
</body>

</html>
