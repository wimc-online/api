<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\PositionOutput;
use App\Entity\Position;

final class PositionOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $outputMatches = PositionOutput::class === $to;
        $inputMatches = $data instanceof Position;

        return $outputMatches && $inputMatches;
    }

    /**
     * {@inheritdoc}
     *
     * @param Position $data
     */
    public function transform($data, string $to, array $context = [])
    {
        $output = new PositionOutput();
        $output->id = $data->getId();
        if (null !== ($context['output']['class'] ?? null)) {
            $output->courier = $data->getCourier();
        }
        $output->tmstp = $data->getTmstp();
        $coordinates = $data->getCoordinates();
        $output->lat = $coordinates->getLat();
        $output->lng = $coordinates->getLng();

        return $output;
    }
}
