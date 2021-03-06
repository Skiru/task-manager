<?php

declare(strict_types=1);

namespace App\Application\Goal\Command;

use App\Application\Goal\Query\GoalQueryInterface;
use App\Domain\Exception\GoalDoesNotExistsException;
use App\Domain\Exception\TaskAlreadyStartedException;
use App\Domain\Exception\TaskException;
use App\Domain\Goal;
use App\Domain\Repository\GoalRepositoryInterface;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\TaskFactory;
use Ramsey\Uuid\Uuid;

final class MarkAsDoneCommandHandler
{
    private GoalQueryInterface $goalQuery;
    private TaskFactory $taskFactory;
    private GoalRepositoryInterface $goalRepository;
    private TaskRepositoryInterface $taskRepository;

    public function __construct(
        GoalQueryInterface $goalQuery,
        TaskFactory $taskFactory,
        GoalRepositoryInterface $goalRepository,
        TaskRepositoryInterface $taskRepository
    ) {
        $this->goalQuery = $goalQuery;
        $this->taskFactory = $taskFactory;
        $this->goalRepository = $goalRepository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param MarkAsDoneCommand $command
     * @throws GoalDoesNotExistsException
     * @throws TaskException
     * @throws TaskAlreadyStartedException
     */
    public function handle(MarkAsDoneCommand $command): void
    {
        $uuid = Uuid::fromString($command->getUuid());

        $goal = $this->goalQuery->findByIdentifier($uuid);
        if (null === $goal) {
            throw new GoalDoesNotExistsException('Goal does not exist!');
        }
        $domainGoal = new Goal($uuid, $goal->getName(), $goal->isFinished(), $goal->getRealizationDescription());

        $task = $this->goalQuery->findTaskByGoal($domainGoal);
        if (null === $task) {
            throw new TaskException('Task does not exist!');
        }

        $task = $this->taskFactory->createFromEntity($task);
        $task->markGoalAsFinished($domainGoal);

        $this->goalRepository->markAsDone($domainGoal);
        if ($task->isFinished()) {
            $this->taskRepository->updateEndDate($task);
        }
    }
}