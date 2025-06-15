<?php

namespace App\Services;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\MessagingException;
use GuzzleHttp\Exception\ConnectException;

class FirebaseService
{
    protected $projectId;
    protected $clientEmail;
    protected $privateKey;

    public function __construct()
    {
        $serviceAccountPath = base_path('storage/truest-hris-8a0f5dff68d2.json');
        $json = json_decode(file_get_contents($serviceAccountPath), true);

        if (is_null($json)) {
            throw new \Exception("service-account.json file missing or invalid.");
        }

        $this->projectId = 'truesthris'; // Replace with your Firebase project ID
        $this->clientEmail = $json['client_email'];
        $this->privateKey = $json['private_key'];
    }

    protected function getAccessToken()
    {
        $now = time();
        $jwt = JWT::encode([
            'iss' => $this->clientEmail,
            'sub' => $this->clientEmail,
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging'
        ], $this->privateKey, 'RS256');

        $client = new Client();
        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ]
        ]);

        return json_decode($response->getBody(), true)['access_token'];
    }

    public function sendNotification($token, $title, $body, $data = [])
    {
        try {
            $accessToken = $this->getAccessToken();
            $fcmUrl = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

            $client = new Client();
            $response = $client->post($fcmUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => $title,
                            'body' => $body
                        ],
                        'data' => $data
                    ]
                ]
            ]);

            return [
                'status' => 'success',
                'message' => 'Notification sent successfully',
                'response' => json_decode($response->getBody(), true)
            ];

        } catch (ConnectException $e) {
            return [
                'status' => 'error',
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        } catch (MessagingException $e) {
            return [
                'status' => 'error',
                'message' => 'FCM error: ' . $e->getMessage()
            ];
        }
    }
}