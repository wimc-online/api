<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Dto\TaskUpdateInput;
use App\Entity\Task;

final class TaskUpdateInputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $alreadyTransformed = $data instanceof TaskUpdateInput;
        $outputMatches = Task::class === $to;
        $inputMatches = TaskUpdateInput::class === ($context['input']['class'] ?? null);

        return !$alreadyTransformed && $outputMatches && $inputMatches;
    }

    /**
     * {@inheritdoc}
     *
     * @param TaskUpdateInput $data
     */
    public function transform($data, string $to, array $context = [])
    {
        $entity = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        if (isset($data->courier)) {
            $entity->setCourier($data->courier);
        }
        if (isset($data->isProcessing)) {
            $entity->setIsProcessing($data->isProcessing);
        }

        return $entity;
    }
}
