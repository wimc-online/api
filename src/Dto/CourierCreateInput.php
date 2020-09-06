<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class CourierCreateInput
{
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Email
     */
    public $email;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $firstName;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $lastName;
}
