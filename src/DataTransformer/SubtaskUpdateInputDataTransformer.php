<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Dto\SubtaskUpdateInput;
use App\Entity\Subtask;

final class SubtaskUpdateInputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $alreadyTransformed = $data instanceof SubtaskUpdateInput;
        $outputMatches = Subtask::class === $to;
        $inputMatches = SubtaskUpdateInput::class === ($context['input']['class'] ?? null);

        return !$alreadyTransformed && $outputMatches && $inputMatches;
    }

    /**
     * {@inheritdoc}
     *
     * @param SubtaskUpdateInput $data
     */
    public function transform($data, string $to, array $context = [])
    {
        $entity = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        if (isset($data->priority)) {
            $entity->setPriority($data->priority);
        }
        if (isset($data->isFinished)) {
            $entity->setIsFinished($data->isFinished);
        }

        return $entity;
    }
}
