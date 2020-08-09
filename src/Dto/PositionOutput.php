<?php

namespace App\Dto;

use Ramsey\Uuid\Uuid;

final class PositionOutput
{
    /**
     * @var Uuid
     */
    public $id;

    /**
     * @var CourierOutput
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
