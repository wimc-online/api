<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\CourierOutput;
use App\Dto\PositionOutput as Output;
use App\Entity\Position as Entity;

final class PositionOutputDataTransformer implements DataTransformerInterface
{
    private $courierOutputDataTransformer;

    public function __construct(CourierOutputDataTransformer $courierOutputDataTransformer)
    {
        $this->courierOutputDataTransformer = $courierOutputDataTransformer;
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
        $output->tmstp = $data->getTmstp();
        $coordinates = $data->getCoordinates();
        $output->lat = $coordinates->getLat();
        $output->lng = $coordinates->getLng();
        $courier = $data->getCourier();
        if (null !== $courier) {
            $output->courier = $this->courierOutputDataTransformer->transform($courier, CourierOutput::class);
        }

        return $output;
    }
}
