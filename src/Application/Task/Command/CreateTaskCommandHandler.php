<?php

declare(strict_types=1);

namespace App\Application\Task\Command;

use App\Application\Goal\Query\GoalQueryInterface;
use App\Application\User\Query\UserQueryInterface;
use App\Domain\Exception\GoalDoesNotExistsException;
use App\Domain\Exception\NotEnoughWorkersException;
use App\Domain\Exception\UserDoesNotExistsException;
use App\Domain\Goal;
use App\Domain\Goals;
use App\Domain\Repository\GoalRepositoryInterface;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\Task;
use App\Domain\Workers;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class CreateTaskCommandHandler
{
    private UserQueryInterface $userQuery;
    private GoalQueryInterface $goalQuery;
    private TaskRepositoryInterface $taskRepository;

    public function __construct(UserQueryInterface $userQuery, GoalQueryInterface $goalQuery, TaskRepositoryInterface $taskRepository)
    {
        $this->userQuery = $userQuery;
        $this->goalQuery = $goalQuery;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @throws GoalDoesNotExistsException
     * @throws NotEnoughWorkersException
     * @throws UserDoesNotExistsException
     */
    public function handle(CreateTaskCommand $command): void
    {
        $user = $this->userQuery->findById($command->getCreatorId());
        if (null === $user) {
            throw new UserDoesNotExistsException('Creator of this task does not exist!');
        }

        if ($command->getRequiredWorkers() <= 0 || $command->getRequiredWorkers() > 1000) {
            throw new NotEnoughWorkersException('Task must have at least 1 required worker and no more than 1000!');
        }

        $task = new Task(
            $user,
            $command->getRequiredWorkers(),
            DateTimeImmutable::createFromFormat('Y-m-d', $command->getStartDate()),
            DateTimeImmutable::createFromFormat('Y-m-d', $command->getEndDate()),
            new Goals($this->getGoals($command->getGoals())),
            new Workers([])
        );

        $this->taskRepository->add($task);
    }

    /**
     * @param array $goals
     * @return Goal[]
     * @throws GoalDoesNotExistsException
     */
    private function getGoals(array $goals): array
    {
        $domainGoals = [];

        foreach ($goals as $goal) {
            $goalUuid = Uuid::fromString($goal);
            $goal = $this->goalQuery->findByIdentifier($goalUuid);
            if (null === $goal) {
                throw new GoalDoesNotExistsException('Goal does not exist, and cannot be applied to that task!');
            }

            $domainGoals[] = new Goal($goalUuid, $goal->getName(), $goal->isFinished(), $goal->getRealizationDescription());
        }

        return $domainGoals;
    }
}