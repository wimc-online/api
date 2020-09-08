<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\DeliveryOutput;
use App\Dto\SubtaskOutput as Output;
use App\Entity\Subtask as Entity;

final class SubtaskOutputDataTransformer implements DataTransformerInterface
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
        $output->task = $data->getTask();
        if (null !== ($context['output']['class'] ?? null)) {
            $output->delivery = $data->getDelivery();
        }
        $output->priority = $data->getPriority();
        $output->isFinished = $data->getIsFinished();

        return $output;
    }
}
