<?php

namespace App\Controller;

use App\DataPersister\DeliveryCourierDataPersister;
use App\Entity\Delivery;
use App\Entity\DeliveryCourier;

class PostDeliveryCourier
{
    private $dataPersister;

    public function __construct(DeliveryCourierDataPersister $dataPersister)
    {
        $this->dataPersister = $dataPersister;
    }

    public function __invoke(DeliveryCourier $data): Delivery
    {
        $this->dataPersister->persist($data);

        return $data->delivery;
    }
}
