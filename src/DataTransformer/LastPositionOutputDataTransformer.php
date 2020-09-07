<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\LastPositionOutput as Output;
use App\Entity\Position as Entity;

final class LastPositionOutputDataTransformer implements DataTransformerInterface
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
        $output->tmstp = $data->getTmstp();
        $coordinates = $data->getCoordinates();
        $output->lat = $coordinates->getLat();
        $output->lng = $coordinates->getLng();

        return $output;
    }
}
