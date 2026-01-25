<?php

namespace App\Services\Firebase;

class FcmService
{
    private string $projectId;
    private string $credentialsPath;

    public function __construct()
    {
        $this->projectId = config('firebase.project_id');
        $this->credentialsPath = config('firebase.credentials');
    }

    /**
     * OAuth2 access token olish (1 soatga yaroqli)
     */
    private function getAccessToken(): string
    {
        $json = json_decode(file_get_contents($this->credentialsPath), true);
        $now = time();

        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $claim = [
            'iss'   => $json['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
        ];

        $b64 = fn ($data) =>
            rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');

        $unsignedJwt = $b64($header) . '.' . $b64($claim);

        openssl_sign(
            $unsignedJwt,
            $signature,
            $json['private_key'],
            'SHA256'
        );

        $jwt = $unsignedJwt . '.' .
            rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://oauth2.googleapis.com/token',
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]),
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);

        return $data['access_token'];
    }

    /**
     * Bitta token ga notification yuborish
     */
    public function sendToToken(
        string $token,
        string $title,
        string $body,
        array $data = []
    ): array {
        $accessToken = $this->getAccessToken();

        $payload = [
            'message' => [
                'token' => $token,

                // 🔔 UI uchun
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],

                // 🧠 Logika / routing uchun
                'data' => $data,

                'android' => [
                    'priority' => 'HIGH',
                ],
            ],
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send",
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
        ]);

        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'http_code' => $code,
            'response'  => json_decode($result, true),
            'raw'       => $result,
        ];
    }
}
