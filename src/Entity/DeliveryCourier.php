<?php

namespace App\Entity;

class DeliveryCourier
{
    /**
     * @var Delivery
     */
    public $delivery;

    /**
     * @var Courier
     */
    public $courier;

    public function getDelivery(): Delivery
    {
        return $this->delivery;
    }

    public function setDelivery(Delivery $delivery): void
    {
        $this->delivery = $delivery;
    }

    public function getCourier(): Courier
    {
        return $this->courier;
    }

    public function setCourier(Courier $courier): void
    {
        $this->courier = $courier;
    }

}
