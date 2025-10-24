<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Admin - CheckVan 2050</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- Style 2050 --}}
    <link href="{{ asset('css/checkvan-2050.css') }}" rel="stylesheet">
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    @livewireStyles
    @stack('styles')
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
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="user-details-2050">
                        <h6>{{ Auth::user()->nom ?? 'Admin' }} {{ Auth::user()->prenom ?? '' }}</h6>
                        <small class="text-muted">Administrateur</small>
                    </div>
                </div>

                <nav class="nav-2050">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-item-2050 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Tableau de bord</span>
                    </a>
                    <a href="{{ route('admin.vehicules') }}"
                        class="nav-item-2050 {{ request()->routeIs('admin.vehicules') ? 'active' : '' }}">
                        <i class="fas fa-car"></i>
                        <span>Véhicules</span>
                    </a>
                    <a href="{{ route('admin.chauffeurs') }}"
                        class="nav-item-2050 {{ request()->routeIs('admin.chauffeurs') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i>
                        <span>Chauffeurs</span>
                    </a>
                    <a href="{{ route('admin.affectations') }}"
                        class="nav-item-2050 {{ request()->routeIs('admin.affectations') ? 'active' : '' }}">
                        <i class="fas fa-link"></i>
                        <span>Affectations</span>
                    </a>
                    <a href="{{ route('admin.taches') }}"
                        class="nav-item-2050 {{ request()->routeIs('admin.taches') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Tâches</span>
                    </a>
                    <a href="{{ route('admin.demandes-taches') }}"
                        class="nav-item-2050 {{ request()->routeIs('admin.demandes-taches') ? 'active' : '' }}">
                        <i class="fas fa-clock"></i>
                        <span>Demandes</span>
                        @php
                            $pendingCount = \App\Models\Tache::where('status', 'en_attente')
                                ->whereHas('vehicule', function ($q) {
                                    $q->where('admin_id', Auth::user()->user_id);
                                })
                                ->count();
                        @endphp
                        @if ($pendingCount > 0)
                            <span class="badge bg-danger-2050 ms-2">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.dommages') }}"
                        class="nav-item-2050 {{ request()->routeIs('admin.dommages') ? 'active' : '' }}">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Dommages</span>
                    </a>
                    <a href="{{ route('admin.map') }}"
                        class="nav-item-2050 {{ request()->routeIs('admin.map') ? 'active' : '' }}">
                        <i class="fas fa-map"></i>
                        <span>Carte</span>
                    </a>
                    <a href="{{ route('admin.notifications') }}"
                        class="nav-item-2050 {{ request()->routeIs('admin.notifications') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
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
                        <h4 class="mb-0 text-gradient">Interface Administrateur</h4>
                        <small class="text-muted">Gestion de flotte et supervision</small>
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

    {{-- jQuery (requis pour Select2) - CDN alternatif plus fiable --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Vérification et fallback jQuery --}}
    <script>
        // Attendre que jQuery soit chargé avec timeout
        let jqueryLoaded = false;
        let attempts = 0;
        const maxAttempts = 10;

        function checkJQuery() {
            attempts++;
            if (typeof jQuery !== 'undefined') {
                jqueryLoaded = true;
                console.log('✅ jQuery chargé avec succès');
                return true;
            } else if (attempts < maxAttempts) {
                console.log(`⏳ Tentative ${attempts}/${maxAttempts} - jQuery pas encore chargé...`);
                setTimeout(checkJQuery, 100);
                return false;
            } else {
                console.error('❌ jQuery n\'a pas pu être chargé après', maxAttempts, 'tentatives');
                // Fallback : charger jQuery depuis un autre CDN
                loadJQueryFallback();
                return false;
            }
        }

        function loadJQueryFallback() {
            console.log('🔄 Tentative de chargement jQuery depuis un CDN de fallback...');
            const script = document.createElement('script');
            script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js';
            script.onload = function() {
                console.log('✅ jQuery chargé depuis le CDN de fallback');
                jqueryLoaded = true;
            };
            script.onerror = function() {
                console.error('❌ Échec du chargement jQuery depuis le CDN de fallback');
            };
            document.head.appendChild(script);
        }

        // Démarrer la vérification
        checkJQuery();
    </script>

    {{-- Select2 2050 - Solution robuste --}}
    <script>
        // Fonction pour détruire proprement Select2
        function destroySelect2() {
            if (typeof jQuery !== 'undefined') {
                $('.select2-2050').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        try {
                            $(this).select2('destroy');
                        } catch (e) {
                            console.log('Select2 déjà détruit');
                        }
                    }
                });
            }
        }

        // petit utilitaire debounce pour éviter les rafales
        function debounce(fn, wait) {
            let t;
            return function(...args) {
                clearTimeout(t);
                t = setTimeout(() => fn.apply(this, args), wait);
            }
        }

        // NE PLUS détruire ici. On (ré)initialise uniquement les selects non initialisés.
        function initializeSelect2Once() {
            if (typeof jQuery === 'undefined') return;

            $('.select2-2050').each(function() {
                const $el = $(this);

                // déjà initialisé ? on ne touche à rien.
                if ($el.hasClass('select2-hidden-accessible')) return;

                $el.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Sélectionner...',
                    allowClear: true,
                    // évite les soucis de z-index / overflow
                    dropdownParent: $('body'),
                    language: {
                        noResults: () => "Aucun résultat trouvé",
                        searching: () => "Recherche en cours..."
                    }
                }).on('select2:open', function() {
                    $('.select2-dropdown').addClass('select2-dropdown-2050');
                });
            });
        }

        const initSelect2Debounced = debounce(initializeSelect2Once, 120);

        // Initialisation au chargement
        document.addEventListener('DOMContentLoaded', function() {
            initSelect2Debounced();
        });

        // Livewire v3 : ne fais que (ré)initialiser les nouveaux éléments, sans détruire
        document.addEventListener('livewire:initialized', () => {
            initSelect2Debounced();

            Livewire.hook('morph.updated', () => {
                // Laisse le DOM se stabiliser, puis (ré)initialise ce qui n'est pas encore init
                initSelect2Debounced();
            });
        });

        // Optionnel : si tu dispatch un event côté PHP, réagis ici
        Livewire.on('refresh-select2', () => initSelect2Debounced());

        // Initialiser les modals Bootstrap
        document.addEventListener('DOMContentLoaded', function() {
            // S'assurer que les modals Bootstrap sont initialisées
            var modalElements = document.querySelectorAll('.modal');
            modalElements.forEach(function(modal) {
                if (modal.classList.contains('show')) {
                    modal.style.display = 'block';
                }
            });
        });
    </script>
    @stack('scripts')
    @livewireScripts
</body>

</html>
