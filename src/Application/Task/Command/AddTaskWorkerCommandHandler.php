<?php

declare(strict_types=1);

namespace App\Application\Task\Command;

use App\Application\Task\Query\TaskQueryInterface;
use App\Domain\Exception\TaskAlreadyStartedException;
use App\Domain\Exception\TaskException;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\TaskFactory;

final class AddTaskWorkerCommandHandler
{
    private TaskQueryInterface $taskQuery;
    private TaskRepositoryInterface $taskRepository;
    private TaskFactory $taskFactory;

    public function __construct(TaskQueryInterface $taskQuery, TaskRepositoryInterface $taskRepository, TaskFactory $taskFactory)
    {
        $this->taskQuery = $taskQuery;
        $this->taskRepository = $taskRepository;
        $this->taskFactory = $taskFactory;
    }

    /**
     * @throws TaskException
     * @throws TaskAlreadyStartedException
     */
    public function handle(AddTaskWorkerCommand $command): void
    {
        $taskEntity = $this->taskQuery->findOneById($command->getTaskId());
        if (null === $taskEntity) {
            throw new TaskException('Task not found exception!');
        }

        $task = $this->taskFactory->createFromEntity($taskEntity);
        $task->addWorker($command->getUser());

        $this->taskRepository->addWorker($task, $command->getUser());
    }
}