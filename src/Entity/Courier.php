<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Dto\CourierCreateInput as CreateInput;
use App\Dto\CourierOutput as Output;
use App\Filter\ActiveCourierFilter;
use App\Repository\CourierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *   output=Output::class,
 *   collectionOperations={
 *       "get",
 *       "post"={"input"=CreateInput::class},
 *   },
 *   itemOperations={
 *       "get",
 *       "delete",
 *   },
 * )
 * @ApiFilter(ActiveCourierFilter::class, properties={"active"})
 * @ORM\Entity(repositoryClass=CourierRepository::class)
 */
class Courier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Position::class, mappedBy="courier", orphanRemoval=true)
     */
    private $positions;

    /**
     * @ApiSubresource()
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="courier", orphanRemoval=true)
     */
    private $tasks;

    public function __construct()
    {
        $this->positions = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Collection|Position[]
     */
    public function getPositions(): Collection
    {
        return $this->positions;
    }

    public function addPosition(Position $position): self
    {
        if (!$this->positions->contains($position)) {
            $this->positions[] = $position;
            $position->setCourier($this);
        }

        return $this;
    }

    public function removePosition(Position $position): self
    {
        if ($this->positions->contains($position)) {
            $this->positions->removeElement($position);
            // set the owning side to null (unless already changed)
            if ($position->getCourier() === $this) {
                $position->setCourier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setCourier($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getCourier() === $this) {
                $task->setCourier(null);
            }
        }

        return $this;
    }
}
