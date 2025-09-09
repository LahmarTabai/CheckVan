<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CheckVan') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @livewireStyles
</head>

<body class="bg-light">
    <div class="container-fluid min-vh-100 d-flex flex-column">
        <header class="py-3">
            @if (Route::has('login'))
                <nav class="d-flex justify-content-end">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-primary me-2">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                            Connexion
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                Inscription
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main class="flex-grow-1 d-flex align-items-center justify-content-center">
            <div class="text-center">
                <h1 class="display-4 mb-4">CheckVan</h1>
                <p class="lead mb-4">Application de gestion de flotte de véhicules</p>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Fonctionnalités</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">✓ Gestion des véhicules et chauffeurs</li>
                                    <li class="mb-2">✓ Prise en charge avec photos</li>
                                    <li class="mb-2">✓ Suivi des tâches en temps réel</li>
                                    <li class="mb-2">✓ Géolocalisation et cartographie</li>
                                    <li class="mb-2">✓ Notifications FCM</li>
                                    <li class="mb-2">✓ Export Excel des rapports</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireScripts
</body>

</html>
