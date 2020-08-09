<?php

namespace App\Controller;

use App\Entity\Courier;
use App\Entity\Delivery;
use App\Repository\CourierRepository;

class GetDeliveryFeaturedCouriers
{
    private $courierRepository;

    public function __construct(CourierRepository $courierRepository)
    {
        $this->courierRepository = $courierRepository;
    }

    /**
     * @return Courier[]
     */
    public function __invoke(Delivery $data): array
    {
        return $this->courierRepository->findAll();
    }
}
