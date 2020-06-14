<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Goal as DomainGoal;
use App\Infrastructure\Entity\Goal;
use App\Infrastructure\Entity\Task as EntityTask;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class TaskFactory
{
    public function createFromEntity(EntityTask $task): Task
    {
        $goalsEntity = $task->getGoals()->toArray();
        $domainGoals = new Goals( array_map(fn(Goal $goal) => new DomainGoal(
            Uuid::fromString($goal->getGoalIdentifier()),
            $goal->getName(),
            $goal->isFinished(),
            $goal->getRealizationDescription()
        ), $goalsEntity));

        $domainWorkers = new Workers($task->getWorkers()->toArray());

        return new Task(
            $task->getCreator(),
            $task->getRequiredWorkers(),
            new DateTimeImmutable($task->getStartDate()->format('Y-m-d H:i:s')),
            new DateTimeImmutable($task->getEndDate()->format('Y-m-d H:i:s')),
            $domainGoals,
            $domainWorkers
        );
    }
}