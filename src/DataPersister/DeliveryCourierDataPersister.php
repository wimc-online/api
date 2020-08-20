<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\DeliveryCourier;
use App\Entity\Subtask;
use App\Entity\Task;
use App\Repository\SubtaskRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

final class DeliveryCourierDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * @var SubtaskRepository
     */
    private $subtaskRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        TaskRepository $taskRepository,
        SubtaskRepository $subtaskRepository
    ) {
        $this->entityManager = $entityManager;
        $this->taskRepository = $taskRepository;
        $this->subtaskRepository = $subtaskRepository;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof DeliveryCourier;
    }

    /**
     * @param DeliveryCourier $data
     */
    public function persist($data, array $context = [])
    {
        if (null !== $data->delivery->getSubtask()) {
            throw new RuntimeException('Not implemented');
        }

        $task = $this->taskRepository->findOneByCourier($data->courier);
        if (null === $task || true === $task->getIsProcessing()) {
            $task = (new Task())
                ->setCourier($data->courier)
                ->setIsProcessing(false);
        }
        $this->entityManager->persist($task);

        $subtask = (new Subtask())
            ->setTask($task)
            ->setDelivery($data->delivery)
            ->setPriority(0) // todo
            ->setIsFinished(false);
        $this->entityManager->persist($subtask);

        return $data;
    }

    public function remove($data, array $context = [])
    {
        throw new RuntimeException('Not implemented');
    }
}
