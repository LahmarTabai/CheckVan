<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FcmServiceSimple;
use App\Models\User;

class TestFcmSimple extends Command
{
    protected $signature = 'fcm:simple {token?} {--title=Test Simple} {--body=Test de notification simple}';
    protected $description = 'Test simple des notifications FCM';

    public function handle()
    {
        $fcmService = new FcmServiceSimple();

        // Vérifier la configuration
        $serverKey = config('services.fcm.key');
        if (empty($serverKey)) {
            $this->error('❌ FCM_SERVER_KEY non configurée');
            return 1;
        }

        $this->info('✅ Configuration FCM trouvée');
        $this->info('🔑 Clé: ' . substr($serverKey, 0, 20) . '...');

        // Obtenir le token
        $token = $this->argument('token');
        if (!$token) {
            $user = User::whereNotNull('fcm_token')->first();
            if ($user) {
                $token = $user->fcm_token;
                $this->info("📱 Token: {$user->nom} {$user->prenom}");
            } else {
                $this->error('❌ Aucun token trouvé');
                return 1;
            }
        }

        $title = $this->option('title');
        $body = $this->option('body');

        $this->info("🚀 Envoi...");
        $this->info("📱 Token: " . substr($token, 0, 30) . '...');
        $this->info("📝 Titre: {$title}");
        $this->info("📄 Message: {$body}");

        // Test simple
        $success = $fcmService->sendToToken($token, $title, $body, [
            'type' => 'simple_test',
            'timestamp' => now()->toISOString()
        ]);

        if ($success) {
            $this->info('✅ Notification envoyée !');
        } else {
            $this->error('❌ Échec de l\'envoi');
            $this->info('🔍 Vérifiez les logs: storage/logs/laravel.log');
        }

        return $success ? 0 : 1;
    }
}
