<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\PositionCreateInput;
use App\Entity\Coordinates;
use App\Entity\Position;
use DateTime;

final class PositionCreateInputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $alreadyTransformed = $data instanceof PositionCreateInput;
        $outputMatches = Position::class === $to;
        $inputMatches = PositionCreateInput::class === ($context['input']['class'] ?? null);

        return !$alreadyTransformed && $outputMatches && $inputMatches;
    }

    /**
     * {@inheritdoc}
     *
     * @param PositionCreateInput $data
     */
    public function transform($data, string $to, array $context = [])
    {
        $entity = new Position();
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
