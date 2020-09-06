<?php

namespace App\Service;

use App\Entity\Courier;
use RuntimeException;

class Keycloak
{
    private $domain;

    private $realm;

    private $clientId;

    private $clientSecret;

    /**
     * @var ?string
     */
    private $token;

    /**
     * @var string
     */
    private $authUrl;

    /**
     * @var string
     */
    private $adminApiUrl;

    public function __construct(string $domain, string $realm, string $clientId, string $clientSecret)
    {
        $this->domain = $domain;
        $this->realm = $realm;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        $this->authUrl = "https://{$this->domain}/auth/realms/{$this->realm}";
        $this->adminApiUrl = "https://{$this->domain}/auth/admin/realms/{$this->realm}";
    }

    /**
     * @throws RuntimeException
     */
    public function createCourier(Courier $courier): void
    {
        if (!$this->isAuthorized()) {
            $this->authorize();
        }

        $options = [
            CURLOPT_URL => "{$this->adminApiUrl}/users",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'attributes' => [
                    'courierId' => $courier->getId(),
                ],
//                'email' => null,
                'enabled' => true,
                'realmRoles' => [
                    'courier',
                ],
                'username' => $courier->getId(),
            ]),
        ] + $this->getDefaultOptions();

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if (201 !== $responseCode) {
            throw new RuntimeException('Error creating courier in Keycloak.');
        }
    }

    /**
     * @throws RuntimeException
     */
    public function readCourier(Courier $courier): array
    {
        if (!$this->isAuthorized()) {
            $this->authorize();
        }

        $query = http_build_query(['username' => $courier->getId(), 'max' => 1]);
        $options = [
            CURLOPT_URL => "{$this->adminApiUrl}/users?{$query}",
        ] + $this->getDefaultOptions();

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $output = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if (200 !== $responseCode) {
            throw new RuntimeException('Error fetching courier from Keycloak.');
        }

        $response = json_decode($output, true);
        if (empty($response[0]['id'])) {
            throw new RuntimeException('Error parsing courier from Keycloak.');
        }

        return $response[0];
    }

    /**
     * @throws RuntimeException
     */
    public function deleteCourier(Courier $courier): void
    {
        if (!$this->isAuthorized()) {
            $this->authorize();
        }

        try {
            $user = $this->readCourier($courier);
        } catch (RuntimeException $exception) {
            return; // let's pretend that if we can't find him he doesn't exists
        }

        $options = [
            CURLOPT_URL => "{$this->adminApiUrl}/users/{$user['id']}",
            CURLOPT_CUSTOMREQUEST => 'DELETE',
        ] + $this->getDefaultOptions();

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if (204 !== $responseCode) {
            throw new RuntimeException('Error removing courier from Keycloak.');
        }
    }

    private function isAuthorized(): bool
    {
        return null !== $this->token;
    }

    /**
     * @throws RuntimeException
     */
    private function authorize(): void
    {
        $options = [
            CURLOPT_URL => "{$this->authUrl}/protocol/openid-connect/token",
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
            CURLOPT_POSTFIELDS => http_build_query([
               'grant_type' => 'client_credentials',
               'client_id' => $this->clientId,
               'client_secret' => $this->clientSecret,
           ]),
        ] + $this->getDefaultOptions();

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $output = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if (200 !== $responseCode) {
            throw new RuntimeException('Error authorizing Keycloak service account.');
        }

        $response = json_decode($output, true);
        if (empty($response['access_token'])) {
            $this->token = null;
            throw new RuntimeException('Error fetching Keycloak authorization token.');
        }
        $this->token = $response['access_token'];
    }

    private function getDefaultOptions(): array
    {
        return [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                "Authorization: Bearer {$this->token}",
            ],
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ];
    }
}
