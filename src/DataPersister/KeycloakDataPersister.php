<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Courier;
use App\Service\Keycloak;
use RuntimeException;

final class KeycloakDataPersister implements ContextAwareDataPersisterInterface
{
    private $decorated;

    private $keycloak;

    public function __construct(ContextAwareDataPersisterInterface $decorated, Keycloak $keycloak)
    {
        $this->decorated = $decorated;
        $this->keycloak = $keycloak;
    }

    public function supports($data, array $context = []): bool
    {
        return $this->decorated->supports($data, $context);
    }

    public function persist($data, array $context = [])
    {
        $result = $this->decorated->persist($data, $context);

        if ($data instanceof Courier && ($context['collection_operation_name'] ?? null) === 'post') {
            if (!$this->createCourier($data)) {
                $this->decorated->remove($data, $context);
                throw new RuntimeException('Cannot create courier in Keycloak.');
            }
        }

        return $result;
    }

    public function remove($data, array $context = [])
    {
        $result = $this->decorated->remove($data, $context);

        if ($data instanceof Courier && ($context['item_operation_name'] ?? null) === 'delete') {
            if (!$this->removeCourier($data)) {
                throw new RuntimeException('Cannot remove courier from Keycloak.');
            }
        }

        return $result;
    }

    private function createCourier(Courier $courier): bool
    {
        try {
            $this->keycloak->createCourier($courier);
        } catch (RuntimeException $exception) {
            return false;
        }

        return true;
    }

    private function removeCourier(Courier $courier): bool
    {
        try {
            $this->keycloak->deleteCourier($courier);
        } catch (RuntimeException $exception) {
            return false;
        }

        return true;
    }
}