<?php

namespace App\Dto;

use Ramsey\Uuid\Uuid;

final class DeliveryOutput
{
    /**
     * @var Uuid
     */
    public $id;

    /**
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $lat;

    /**
     * @var string
     */
    public $lng;
}
