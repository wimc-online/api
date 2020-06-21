<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DeliveryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=DeliveryRepository::class)
 */
class Delivery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Embedded(class="Coordinates", columnPrefix=false)
     */
    private $coordinates;

    /**
     * @ORM\Column(type="text")
     */
    private $address;

    /**
     * @ORM\OneToOne(targetEntity=Subtask::class, mappedBy="delivery", cascade={"persist", "remove"})
     */
    private $subtask;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getSubtask(): ?Subtask
    {
        return $this->subtask;
    }

    public function setSubtask(Subtask $subtask): self
    {
        $this->subtask = $subtask;

        // set the owning side of the relation if necessary
        if ($subtask->getDelivery() !== $this) {
            $subtask->setDelivery($this);
        }

        return $this;
    }
}
