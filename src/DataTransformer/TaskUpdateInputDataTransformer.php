<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Dto\TaskUpdateInput as Input;
use App\Entity\Task as Entity;

final class TaskUpdateInputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $alreadyTransformed = $data instanceof Input;
        $outputMatches = Entity::class === $to;
        $inputMatches = Input::class === ($context['input']['class'] ?? null);

        return !$alreadyTransformed && $outputMatches && $inputMatches;
    }

    /**
     * {@inheritdoc}
     *
     * @param Input $data
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
