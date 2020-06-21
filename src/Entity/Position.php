<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PositionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PositionRepository::class)
 */
class Position
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Courier::class, inversedBy="positions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $courier;

    /**
     * @ORM\Embedded(class="Coordinates", columnPrefix=false)
     */
    private $coordinates;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tmstp;

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

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function setCoordinates(Coordinates $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getTmstp(): ?\DateTimeInterface
    {
        return $this->tmstp;
    }

    public function setTmstp(\DateTimeInterface $tmstp): self
    {
        $this->tmstp = $tmstp;

        return $this;
    }
}
