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

    /**
     * @var string
     */
    private $resetPassword;

    public function __construct(string $domain, string $realm, string $clientId, string $clientSecret, string $resetPassword)
    {
        $this->domain = $domain;
        $this->realm = $realm;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->resetPassword = $resetPassword;

        $this->authUrl = "https://{$this->domain}/auth/realms/{$this->realm}";
        $this->adminApiUrl = "https://{$this->domain}/auth/admin/realms/{$this->realm}";
    }

    /**
     * @throws RuntimeException
     */
    public function createCourier(Courier $courier): Courier
    {
        if (!$this->isAuthorized()) {
            $this->authorize();
        }

        $options = [
            CURLOPT_HEADER => true,
            CURLOPT_URL => "{$this->adminApiUrl}/users",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'email' => $courier->getEmail(),
                'enabled' => true,
                'firstName' => $courier->getFirstName(),
                'lastName' => $courier->getLastName(),
                'realmRoles' => [
                    'courier',
                ],
                'username' => mb_strtolower("{$courier->getFirstName()}.{$courier->getLastName()}"),
            ]),
        ] + $this->getDefaultOptions();

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $output = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($output, 0, $headerSize);
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if (201 !== $responseCode) {
            throw new RuntimeException('Error creating courier in Keycloak.');
        }

        $headerLines = explode("\r\n", $header);
        array_shift($headerLines); // response code
        $headers = [];
        foreach ($headerLines as $i => $line) {
            if (empty($line)) {
                continue;
            }
            [$key, $value] = explode(': ', $line, 2);
            $headers[mb_strtolower($key)] = $value;
        }
        if (empty($headers['location'])) {
            throw new RuntimeException('Error parsing courier from Keycloak.');
        }
        $locationEls = explode('/', $headers['location']);

        $courier->setId($locationEls[array_key_last($locationEls)]);

        return $courier;
    }

    /**
     * @throws RuntimeException
     */
    public function updateCourierId(Courier $courier): Courier
    {
        if (!$this->isAuthorized()) {
            $this->authorize();
        }

        $options = [
            CURLOPT_URL => "{$this->adminApiUrl}/users/{$courier->getId()}",
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'attributes' => [
                    'courierId' => $courier->getId(),
                ],
            ]),
        ] + $this->getDefaultOptions();

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if (204 !== $responseCode) {
            throw new RuntimeException('Error updating courier in Keycloak.');
        }

        return $courier;
    }

    /**
     * @throws RuntimeException
     */
    public function resetCourierPassword(Courier $courier): void
    {
        if (!$this->isAuthorized()) {
            $this->authorize();
        }

        $options = [
                CURLOPT_URL => "{$this->adminApiUrl}/users/{$courier->getId()}/reset-password",
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode([
                    'value' => $this->resetPassword,
                ]),
            ] + $this->getDefaultOptions();

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if (204 !== $responseCode) {
            throw new RuntimeException('Error resetting courier password in Keycloak.');
        }
    }

    /**
     * @throws RuntimeException
     */
    public function deleteCourier(Courier $courier): void
    {
        if (!$this->isAuthorized()) {
            $this->authorize();
        }

        $options = [
            CURLOPT_URL => "{$this->adminApiUrl}/users/{$courier->getId()}",
            CURLOPT_CUSTOMREQUEST => 'DELETE',
        ] + $this->getDefaultOptions();

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if (404 === $responseCode) {
            return;
        }
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
