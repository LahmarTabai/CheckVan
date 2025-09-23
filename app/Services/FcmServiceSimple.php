<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmServiceSimple
{
    public function __construct(private string $serverKey = '')
    {
        $this->serverKey = $this->serverKey ?: config('services.fcm.key');
    }

    public function sendToToken(string $token, string $title, string $body, array $data = []): bool
    {
        if (!$this->serverKey || !$token) {
            Log::warning('FCM: Clé serveur ou token manquant');
            return false;
        }

        // Version simple avec l'ancienne API FCM
        $payload = [
            'to' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default'
            ],
            'data' => $data
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $this->serverKey,
                'Content-Type' => 'application/json'
            ])
            ->withOptions([
                'verify' => false, // Désactiver SSL pour Windows
                'timeout' => 30
            ])
            ->post('https://fcm.googleapis.com/fcm/send', $payload);

            Log::info('FCM Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('FCM Exception', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }
}

