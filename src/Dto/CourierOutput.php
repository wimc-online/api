<?php

namespace App\Dto;

use Ramsey\Uuid\Uuid;

final class CourierOutput
{
    /**
     * @var Uuid
     */
    public $id;

    /**
     * @var ?LastPositionOutput
     */
    public $lastPosition;
}
