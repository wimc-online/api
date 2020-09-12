<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\DeliveryCreateInput;
use App\Entity\Coordinates;
use App\Entity\Delivery;

final class DeliveryCreateInputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $alreadyTransformed = $data instanceof DeliveryCreateInput;
        $outputMatches = Delivery::class === $to;
        $inputMatches = DeliveryCreateInput::class === ($context['input']['class'] ?? null);

        return !$alreadyTransformed && $outputMatches && $inputMatches;
    }

    /**
     * {@inheritdoc}
     *
     * @param DeliveryCreateInput $data
     */
    public function transform($data, string $to, array $context = [])
    {
        $entity = new Delivery();
        $entity->setAddress($data->address);
        $coordinates = new Coordinates();
        $coordinates->setLat($data->lat);
        $coordinates->setLng($data->lng);
        $entity->setCoordinates($coordinates);

        return $entity;
    }
}
