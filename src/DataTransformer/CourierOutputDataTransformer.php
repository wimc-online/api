<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\CourierOutput;
use App\Entity\Courier;

final class CourierOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $outputMatches = CourierOutput::class === $to;
        $inputMatches = $data instanceof Courier;

        return $outputMatches && $inputMatches;
    }
    /**
     * {@inheritdoc}
     *
     * @param Courier $data
     */
    public function transform($data, string $to, array $context = [])
    {
        $output = new CourierOutput();
        $output->id = $data->getId();
        $output->lastPosition = $data->getPositions()->isEmpty() ? null : $data->getPositions()->first();

        return $output;
    }
}
