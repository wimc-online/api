<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Dto\DeliveryCourierCreateInput;
use App\Entity\Delivery;
use App\Entity\DeliveryCourier;

final class DeliveryCourierCreateInputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $alreadyTransformed = $data instanceof DeliveryCourierCreateInput;
        $outputMatches = Delivery::class === $to;
        $inputMatches = DeliveryCourierCreateInput::class === ($context['input']['class'] ?? null);

        return !$alreadyTransformed && $outputMatches && $inputMatches;
    }

    /**
     * {@inheritdoc}
     *
     * @param DeliveryCourierCreateInput $data
     */
    public function transform($data, string $to, array $context = [])
    {
        $entity = new DeliveryCourier();
        $entity->setDelivery($context[AbstractItemNormalizer::OBJECT_TO_POPULATE]);
        $entity->setCourier($data->courier);

        return $entity;
    }
}
