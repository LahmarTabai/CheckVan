<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test FCM - CheckVan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/checkvan-2050.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-2050">
                    <div class="card-header-2050">
                        <h3 class="mb-0">
                            <i class="fas fa-bell me-2"></i>Test des Notifications FCM
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle me-2"></i>Instructions</h5>
                            <ol>
                                <li><strong>Configurez votre clé FCM</strong> dans le fichier <code>.env</code></li>
                                <li><strong>Enregistrez un token FCM</strong> via l'API ou la commande</li>
                                <li><strong>Testez l'envoi</strong> avec les boutons ci-dessous</li>
                            </ol>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card bg-dark border-primary">
                                    <div class="card-body">
                                        <h5><i class="fas fa-terminal me-2"></i>Test via Commande</h5>
                                        <p class="text-muted">Utilisez la commande Artisan pour tester</p>
                                        <div class="bg-dark p-3 rounded">
                                            <code class="text-success">php artisan fcm:test</code>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                Options: <code>--title="Mon titre"</code> <code>--body="Mon message"</code>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card bg-dark border-primary">
                                    <div class="card-body">
                                        <h5><i class="fas fa-code me-2"></i>Test via API</h5>
                                        <p class="text-muted">Enregistrez un token FCM</p>
                                        <div class="bg-dark p-3 rounded">
                                            <code class="text-info">POST /api/fcm-token</code>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                Body: <code>{"token": "votre_token_ici"}</code>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h5><i class="fas fa-cogs me-2"></i>Configuration Requise</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>1. Fichier .env</h6>
                                    <div class="bg-dark p-3 rounded">
                                        <code class="text-warning">FCM_SERVER_KEY=votre_cle_firebase</code>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>2. Vérification</h6>
                                    <a href="/admin/notifications" class="btn btn-primary-2050">
                                        <i class="fas fa-bell me-2"></i>Voir les Notifications
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h5><i class="fas fa-bug me-2"></i>Débogage</h5>
                            <div class="bg-dark p-3 rounded">
                                <p class="mb-2"><strong>Logs Laravel:</strong> <code>storage/logs/laravel.log</code></p>
                                <p class="mb-2"><strong>Vérifier la config:</strong> <code>php artisan config:show services.fcm</code></p>
                                <p class="mb-0"><strong>Test de connexion:</strong> <code>php artisan fcm:test</code></p>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <a href="/" class="btn btn-outline-2050 me-3">
                                <i class="fas fa-home me-2"></i>Accueil
                            </a>
                            <a href="/admin/dashboard" class="btn btn-primary-2050">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

