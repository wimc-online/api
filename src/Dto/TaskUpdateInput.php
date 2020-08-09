<?php

namespace App\Dto;

use App\Entity\Courier;

final class TaskUpdateInput
{
    /**
     * @var ?Courier
     */
    public $courier;

    /**
     * @var ?bool
     */
    public $isProcessing;
}
