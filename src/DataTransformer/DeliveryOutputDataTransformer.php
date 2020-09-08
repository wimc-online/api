<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\DeliveryOutput as Output;
use App\Entity\Delivery as Entity;

final class DeliveryOutputDataTransformer implements DataTransformerInterface
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
