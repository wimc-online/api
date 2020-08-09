<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\DeliveryCreateInput as Input;
use App\Entity\Coordinates;
use App\Entity\Delivery as Entity;

final class DeliveryCreateInputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $alreadyTransformed = $data instanceof Input;
        $outputMatches = Entity::class === $to;
        $inputMatches = Input::class === ($context['input']['class'] ?? null);

        return !$alreadyTransformed && $outputMatches && $inputMatches;
    }

    /**
     * {@inheritdoc}
     *
     * @param Input $data
     */
    public function transform($data, string $to, array $context = [])
    {
        $entity = new Entity();
        $entity->setAddress($data->address);
        $coordinates = new Coordinates();
        $coordinates->setLat($data->lat);
        $coordinates->setLng($data->lng);
        $entity->setCoordinates($coordinates);

        return $entity;
    }
}
