<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=Subtask::class, mappedBy="task", orphanRemoval=true)
     */
    private $subtasks;

    public function __construct()
    {
        $this->subtasks = new ArrayCollection();
    }

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

    /**
     * @return Collection|Subtask[]
     */
    public function getSubtasks(): Collection
    {
        return $this->subtasks;
    }

    public function addSubtask(Subtask $subtask): self
    {
        if (!$this->subtasks->contains($subtask)) {
            $this->subtasks[] = $subtask;
            $subtask->setTask($this);
        }

        return $this;
    }

    public function removeSubtask(Subtask $subtask): self
    {
        if ($this->subtasks->contains($subtask)) {
            $this->subtasks->removeElement($subtask);
            // set the owning side to null (unless already changed)
            if ($subtask->getTask() === $this) {
                $subtask->setTask(null);
            }
        }

        return $this;
    }
}
