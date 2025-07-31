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
            // Validate that $data is an associative array (map)
            if (!is_array($data) || array_keys($data) === range(0, count($data) - 1)) {
                throw new \Exception('The "data" parameter must be an associative array.');
            }

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

            $responseBody = json_decode($response->getBody(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to decode JSON response: ' . json_last_error_msg());
            }

            return [
                'status' => 'success',
                'message' => 'Notification sent successfully',
                'response' => $responseBody
            ];

        } catch (ConnectException $e) {
            \Log::error('Connection error while sending notification', [
                'token' => $token,
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            return [
                'status' => 'error',
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        } catch (MessagingException $e) {
            \Log::error('FCM error while sending notification', [
                'token' => $token,
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            return [
                'status' => 'error',
                'message' => 'FCM error: ' . $e->getMessage()
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            $decodedResponse = json_decode($responseBody, true);

            // Log detailed error response
            \Log::error('Client error while sending notification', [
                'token' => $token,
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'error' => $e->getMessage(),
                'response' => $decodedResponse
            ]);

            // Extract meaningful error message if available
            $errorMessage = $decodedResponse['error']['message'] ?? 'Unknown error';
            return [
                'status' => 'error',
                'message' => 'Client error: ' . $errorMessage,
                'response' => $decodedResponse
            ];
        } catch (\Exception $e) {
            \Log::error('General error while sending notification', [
                'token' => $token,
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            return [
                'status' => 'error',
                'message' => 'General error: ' . $e->getMessage()
            ];
        }
    }
}