<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FcmService
{
    public function __construct(private string $serverKey = '')
    {
        $this->serverKey = $this->serverKey ?: config('services.fcm.key');
    }

    public function sendToToken(string $token, string $title, string $body, array $data = []): bool
    {
        if (!$this->serverKey || !$token) {
            return false;
        }

        $payload = [
            'to' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ];

        $response = Http::withToken($this->serverKey)
            ->acceptJson()
            ->post('https://fcm.googleapis.com/fcm/send', $payload);

        return $response->successful();
    }
}



