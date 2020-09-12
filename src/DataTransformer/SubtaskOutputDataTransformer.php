<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\SubtaskOutput;
use App\Entity\Subtask;

final class SubtaskOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $outputMatches = SubtaskOutput::class === $to;
        $inputMatches = $data instanceof Subtask;

        return $outputMatches && $inputMatches;
    }

    /**
     * {@inheritdoc}
     *
     * @param Subtask $data
     */
    public function transform($data, string $to, array $context = [])
    {
        $output = new SubtaskOutput();
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
