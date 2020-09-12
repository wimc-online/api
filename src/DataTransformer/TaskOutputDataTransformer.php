<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\TaskOutput;
use App\Entity\Task;

final class TaskOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $outputMatches = TaskOutput::class === $to;
        $inputMatches = $data instanceof Task;

        return $outputMatches && $inputMatches;
    }

    /**
     * {@inheritdoc}
     *
     * @param Task $data
     */
    public function transform($data, string $to, array $context = [])
    {
        $output = new TaskOutput();
        $output->id = $data->getId();
        $output->courier = $data->getCourier();
        $output->isProcessing = $data->getIsProcessing();

        return $output;
    }
}
