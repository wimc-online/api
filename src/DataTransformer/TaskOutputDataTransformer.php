<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\TaskOutput as Output;
use App\Entity\Task as Entity;

final class TaskOutputDataTransformer implements DataTransformerInterface
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
        $output->courier = $data->getCourier();
        $output->isProcessing = $data->getIsProcessing();

        return $output;
    }
}
