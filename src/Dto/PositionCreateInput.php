<?php

namespace App\Dto;

use App\Entity\Courier;

final class PositionCreateInput
{
    /**
     * @var Courier
     */
    public $courier;

    /**
     * @var string
     */
    public $tmstp;

    /**
     * @var string
     */
    public $lat;

    /**
     * @var string
     */
    public $lng;
}
