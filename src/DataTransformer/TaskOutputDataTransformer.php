<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\CourierOutput;
use App\Dto\TaskOutput as Output;
use App\Entity\Task as Entity;

final class TaskOutputDataTransformer implements DataTransformerInterface
{
    private $courierOutputDataTransformer;

    public function __construct(CourierOutputDataTransformer $courierOutputDataTransformer)
    {
        $this->courierOutputDataTransformer = $courierOutputDataTransformer;
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
        $output->isProcessing = $data->getIsProcessing();
        $courier = $data->getCourier();
        if (null !== $courier) {
            $output->courier = $this->courierOutputDataTransformer->transform($courier, CourierOutput::class);
        }

        return $output;
    }
}
