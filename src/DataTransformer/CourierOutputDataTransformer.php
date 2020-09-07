<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\CourierOutput as Output;
use App\Dto\LastPositionOutput;
use App\Entity\Courier as Entity;

final class CourierOutputDataTransformer implements DataTransformerInterface
{
    private $lastPositionOutputDataTransformer;

    public function __construct(LastPositionOutputDataTransformer $lastPositionOutputDataTransformer)
    {
        $this->lastPositionOutputDataTransformer = $lastPositionOutputDataTransformer;
    }

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
        $lastPosition = $data->getLastPosition();
        if (null !== $lastPosition) {
            $output->lastPosition = $this->lastPositionOutputDataTransformer->transform($lastPosition, LastPositionOutput::class);
        }

        return $output;
    }
}
