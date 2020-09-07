<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\DeliveryOutput;
use App\Dto\SubtaskOutput as Output;
use App\Entity\Subtask as Entity;

final class SubtaskOutputDataTransformer implements DataTransformerInterface
{
    private $deliveryOutputDataTransformer;

    public function __construct(DeliveryOutputDataTransformer $deliveryOutputDataTransformer)
    {
        $this->deliveryOutputDataTransformer = $deliveryOutputDataTransformer;
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
        $output->priority = $data->getPriority();
        $output->isFinished = $data->getIsFinished();
        $delivery = $data->getDelivery();
        if (null !== $delivery) {
            $output->delivery = $this->deliveryOutputDataTransformer->transform($delivery, DeliveryOutput::class);
        }

        return $output;
    }
}
