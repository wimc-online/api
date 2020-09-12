<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\DeliveryOutput;
use App\Entity\Delivery;

final class DeliveryOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $outputMatches = DeliveryOutput::class === $to;
        $inputMatches = $data instanceof Delivery;

        return $outputMatches && $inputMatches;
    }

    /**
     * {@inheritdoc}
     *
     * @param Delivery $data
     */
    public function transform($data, string $to, array $context = [])
    {
        $output = new DeliveryOutput();
        $output->id = $data->getId();
        $output->address = $data->getAddress();
        $coordinates = $data->getCoordinates();
        $output->lat = $coordinates->getLat();
        $output->lng = $coordinates->getLng();
        if (null !== ($context['output']['class'] ?? null)) {
            $output->subtask = $data->getSubtask();
        }

        return $output;
    }
}
