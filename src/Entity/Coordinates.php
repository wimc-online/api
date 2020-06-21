<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Coordinates
{
    /**
     * @ORM\Column(type="decimal", precision=8, scale=6)
     */
    private $lat;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=6)
     */
    private $lng;

    public function getLat(): string
    {
        return $this->lat;
    }

    public function setLat(string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): string
    {
        return $this->lng;
    }

    public function setLng(string $lng): self
    {
        $this->lng = $lng;

        return $this;
    }
}
