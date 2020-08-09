<?php

namespace App\Dto;

final class SubtaskUpdateInput
{
    /**
     * @var ?int
     */
    public $priority;

    /**
     * @var ?bool
     */
    public $isFinished;
}
