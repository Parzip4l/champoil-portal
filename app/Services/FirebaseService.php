<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        // Path ke file JSON kunci layanan
        $serviceAccountPath = base_path('storage/truest-hris-8a0f5dff68d2.json');

        // Inisialisasi Firebase
        $factory = (new Factory)
            ->withServiceAccount($serviceAccountPath);

        // Inisialisasi layanan Messaging
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($token, $title, $body, $data = [])
    {
        $message = [
            'token' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ];

        try {
            $this->messaging->send($message);
            return ['status' => 'success', 'message' => 'Notification sent'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
