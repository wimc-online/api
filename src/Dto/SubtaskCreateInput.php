<?php

namespace App\Dto;

use App\Entity\Delivery;
use App\Entity\Task;

final class SubtaskCreateInput
{
    /**
     * @var Task
     */
    public $task;

    /**
     * @var Delivery
     */
    public $delivery;
}
