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
        if ($data instanceof Courier && ($context['collection_operation_name'] ?? null) === 'post') {
            if (!$this->createCourier($data)) {
                throw new RuntimeException('Cannot create courier in Keycloak.');
            }
        }

        $result = $this->decorated->persist($data, $context);

        if ($result instanceof Courier && ($context['collection_operation_name'] ?? null) === 'post') {
            if (!$this->updateCourierId($result)) {
                $this->decorated->remove($result, $context);
                $this->removeCourier($result);
                throw new RuntimeException('Cannot update courierId in Keycloak.');
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
            $user = $this->keycloak->createCourier($courier);
        } catch (RuntimeException $exception) {
            return false;
        }

        $courier->setId($user['id']);

        return true;
    }

    private function updateCourierId(Courier $courier): bool
    {
        try {
            $this->keycloak->updateCourierId($courier);
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
