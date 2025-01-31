<?php

namespace App\Dto;

use Ramsey\Uuid\Uuid;

final class SubtaskOutput
{
    /**
     * @var Uuid
     */
    public $id;

    /**
     * @var TaskOutput
     */
    public $task;

    /**
     * @var DeliveryOutput
     */
    public $delivery;

    /**
     * @var int
     */
    public $priority;

    /**
     * @var bool
     */
    public $isFinished;
}
