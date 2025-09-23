<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    public function __construct(private string $serverKey = '')
    {
        $this->serverKey = $this->serverKey ?: config('services.fcm.key');
    }

    public function sendToToken(string $token, string $title, string $body, array $data = []): bool
    {
        if (!$this->serverKey || !$token) {
            Log::warning('FCM: Clé serveur ou token manquant', [
                'has_server_key' => !empty($this->serverKey),
                'has_token' => !empty($token)
            ]);
            return false;
        }

        // Utiliser l'API FCM v1 (plus moderne)
        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
                'data' => $data,
                'android' => [
                    'priority' => 'high'
                ],
                'apns' => [
                    'headers' => [
                        'apns-priority' => '10'
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->serverKey,
                'Content-Type' => 'application/json'
            ])
            ->withOptions([
                'verify' => false, // Désactiver la vérification SSL pour Windows
                'timeout' => 30
            ])
            ->post('https://fcm.googleapis.com/v1/projects/checkvan-notifications/messages:send', $payload);

            if ($response->successful()) {
                Log::info('FCM: Notification envoyée avec succès', [
                    'token' => substr($token, 0, 20) . '...',
                    'title' => $title
                ]);
                return true;
            } else {
                Log::error('FCM: Erreur lors de l\'envoi', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('FCM: Exception lors de l\'envoi', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Envoyer une notification à plusieurs tokens
     */
    public function sendToMultipleTokens(array $tokens, string $title, string $body, array $data = []): array
    {
        $results = [];
        foreach ($tokens as $token) {
            $results[$token] = $this->sendToToken($token, $title, $body, $data);
        }
        return $results;
    }

    /**
     * Vérifier si un token FCM est valide
     */
    public function isTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        // Test simple avec une notification silencieuse
        return $this->sendToToken($token, '', '', ['test' => 'true']);
    }
}



