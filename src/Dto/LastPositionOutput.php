<?php

namespace App\Dto;

use Ramsey\Uuid\Uuid;

final class LastPositionOutput
{
    /**
     * @var Uuid
     */
    public $id;

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
