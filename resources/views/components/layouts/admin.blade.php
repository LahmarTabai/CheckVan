<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Admin - CheckVan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @livewireStyles
</head>

<body>
    <div class="d-flex">
        {{-- Sidebar --}}
        <div class="bg-dark text-white p-3 vh-100" style="width: 250px;">
            <h4 class="mb-4">🚐 CheckVan</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">🏠 Tableau de bord</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.vehicules') }}" class="nav-link text-white">🚗 Véhicules</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.chauffeurs') }}" class="nav-link text-white">👨‍✈️ Chauffeurs</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.affectations') }}" class="nav-link text-white">🔗 Affectations</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.taches') }}" class="nav-link text-white">📋 Tâches</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.map') }}" class="nav-link text-white">🗺️ Carte</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.notifications') }}" class="nav-link text-white">🔔 Notifications</a>
                </li>
                <li class="nav-item mt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-light w-100">Déconnexion</button>
                    </form>
                </li>
            </ul>
        </div>

        {{-- Contenu principal --}}
        <div class="flex-grow-1 p-4">
            {{ $slot }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>

</html>
