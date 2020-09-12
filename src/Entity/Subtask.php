<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\SubtaskUpdateInput;
use App\Dto\SubtaskOutput;
use App\Repository\SubtaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *   output=SubtaskOutput::class,
 *   collectionOperations={},
 *   itemOperations={
 *       "get",
 *       "patch"={"input"=SubtaskUpdateInput::class},
 *       "delete",
 *   },
 *   normalizationContext={
 *       "skip_null_values"=false,
 *   },
 * )
 * @ORM\Entity(repositoryClass=SubtaskRepository::class)
 */
class Subtask
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Task::class, inversedBy="subtasks", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $task;

    /**
     * @ORM\OneToOne(targetEntity=Delivery::class, inversedBy="subtask", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $delivery;

    /**
     * @ORM\Column(type="integer")
     */
    private $priority;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_finished;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }

    public function setDelivery(Delivery $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getIsFinished(): ?bool
    {
        return $this->is_finished;
    }

    public function setIsFinished(bool $is_finished): self
    {
        $this->is_finished = $is_finished;

        return $this;
    }
}
