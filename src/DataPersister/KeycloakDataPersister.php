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
            try {
                $data = $this->keycloak->createCourier($data);
            } catch (RuntimeException $exception) {
                throw new RuntimeException('Cannot create courier in Keycloak.');
            }
            try {
                $data = $this->keycloak->updateCourierId($data);
            } catch (RuntimeException $exception) {
                $this->keycloak->deleteCourier($data);
                throw new RuntimeException('Cannot update courierId in Keycloak.');
            }
            try {
                $this->keycloak->resetCourierPassword($data);
            } catch (RuntimeException $exception) {
                $this->keycloak->deleteCourier($data);
                throw new RuntimeException('Cannot reset courier password in Keycloak.');
            }
        }

        return $this->decorated->persist($data, $context);
    }

    public function remove($data, array $context = [])
    {
        $this->decorated->remove($data, $context);

        if ($data instanceof Courier && ($context['item_operation_name'] ?? null) === 'delete') {
            try {
                $this->keycloak->deleteCourier($data);
            } catch (RuntimeException $exception) {
                throw new RuntimeException('Cannot remove courier from Keycloak.');
            }
        }
    }
}
