<?php

namespace App\Controller;

use App\Entity\Courier;
use App\Entity\Task;
use App\Repository\CourierRepository;

class GetTaskFeaturedCouriers
{
    private $courierRepository;

    public function __construct(CourierRepository $courierRepository)
    {
        $this->courierRepository = $courierRepository;
    }

    /**
     * @return Courier[]
     */
    public function __invoke(Task $data): array
    {
        return $this->courierRepository->findAll();
    }
}
