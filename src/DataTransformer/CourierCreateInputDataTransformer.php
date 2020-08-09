<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\CourierCreateInput as Input;
use App\Entity\Courier as Entity;

final class CourierCreateInputDataTransformer implements DataTransformerInterface
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
        $entity = new Entity();

        return $entity;
    }
}
