<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\PositionCreateInput as Input;
use App\Entity\Coordinates;
use App\Entity\Position as Entity;
use DateTime;

final class PositionCreateInputDataTransformer implements DataTransformerInterface
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
        $entity->setCourier($data->courier);
        $tmstp = new DateTime($data->tmstp);
        $entity->setTmstp($tmstp);
        $coordinates = new Coordinates();
        $coordinates->setLat($data->lat);
        $coordinates->setLng($data->lng);
        $entity->setCoordinates($coordinates);

        return $entity;
    }
}
