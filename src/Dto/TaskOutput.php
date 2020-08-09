<?php

namespace App\Dto;

use Ramsey\Uuid\Uuid;

final class TaskOutput
{
    /**
     * @var Uuid
     */
    public $id;

    /**
     * @var ?CourierOutput
     */
    public $courier;

    /**
     * @var bool
     */
    public $isProcessing;
}
