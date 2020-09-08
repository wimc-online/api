<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\PositionOutput as Output;
use App\Entity\Position as Entity;

final class PositionOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $outputMatches = Output::class === $to;
        $inputMatches = $data instanceof Entity;

        return $outputMatches && $inputMatches;
    }

    /**
     * {@inheritdoc}
     *
     * @param Entity $data
     */
    public function transform($data, string $to, array $context = [])
    {
        $output = new Output();
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
