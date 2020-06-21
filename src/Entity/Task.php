<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Courier::class, inversedBy="tasks")
     */
    private $courier;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_processing;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCourier(): ?Courier
    {
        return $this->courier;
    }

    public function setCourier(?Courier $courier): self
    {
        $this->courier = $courier;

        return $this;
    }

    public function getIsProcessing(): ?bool
    {
        return $this->is_processing;
    }

    public function setIsProcessing(bool $is_processing): self
    {
        $this->is_processing = $is_processing;

        return $this;
    }
}
