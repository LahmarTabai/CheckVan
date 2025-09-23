<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FcmService;
use App\Models\User;

class TestFcmNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:test {token?} {--title=Test Notification} {--body=Ceci est un test de notification FCM}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester l\'envoi de notifications FCM';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fcmService = app(FcmService::class);
        
        // Vérifier la configuration
        $serverKey = config('services.fcm.key');
        if (empty($serverKey)) {
            $this->error('❌ FCM_SERVER_KEY n\'est pas configurée dans le fichier .env');
            $this->info('💡 Ajoutez FCM_SERVER_KEY=votre_cle_ici dans votre fichier .env');
            return 1;
        }

        $this->info('✅ Configuration FCM trouvée');
        $this->info('🔑 Clé serveur: ' . substr($serverKey, 0, 20) . '...');

        // Obtenir le token
        $token = $this->argument('token');
        
        if (!$token) {
            // Chercher un utilisateur avec un token FCM
            $user = User::whereNotNull('fcm_token')->first();
            if ($user) {
                $token = $user->fcm_token;
                $this->info("📱 Token trouvé pour l'utilisateur: {$user->nom} {$user->prenom}");
            } else {
                $this->error('❌ Aucun token FCM trouvé dans la base de données');
                $this->info('💡 Enregistrez d\'abord un token FCM via l\'API: POST /api/fcm-token');
                return 1;
            }
        }

        $title = $this->option('title');
        $body = $this->option('body');

        $this->info("🚀 Envoi de la notification...");
        $this->info("📱 Token: " . substr($token, 0, 30) . '...');
        $this->info("📝 Titre: {$title}");
        $this->info("📄 Message: {$body}");

        // Envoyer la notification
        $success = $fcmService->sendToToken($token, $title, $body, [
            'type' => 'test',
            'timestamp' => now()->toISOString()
        ]);

        if ($success) {
            $this->info('✅ Notification envoyée avec succès !');
            $this->info('📱 Vérifiez votre appareil mobile');
        } else {
            $this->error('❌ Échec de l\'envoi de la notification');
            $this->info('🔍 Vérifiez les logs: storage/logs/laravel.log');
        }

        return $success ? 0 : 1;
    }
}
