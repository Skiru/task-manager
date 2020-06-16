<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exception\GoalDoesNotExistsException;
use App\Domain\Exception\NotPermittedOperation;
use App\Domain\Exception\TaskAlreadyStartedException;
use App\Infrastructure\Entity\User;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Task
{
    private UuidInterface $identifier;
    private User $creator;
    private int $requiredWorkers;
    private DateTimeImmutable $startDate;
    private DateTimeImmutable $endDate;
    private Goals $goals;
    private Workers $workers;

    public function __construct(
        UuidInterface $uuid,
        User $creator,
        int $requiredWorkers,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
        Goals $goals,
        Workers $workers
    ) {
        $this->identifier = $uuid;
        $this->creator = $creator;
        $this->requiredWorkers = $requiredWorkers;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->goals = $goals;
        $this->workers = $workers;
    }

    public function isStarted(): bool
    {
        return $this->startDate < new DateTimeImmutable();
    }

    public function isFinished(): bool
    {
        return $this->endDate <= new DateTimeImmutable();
    }

    /**
     * @throws TaskAlreadyStartedException
     */
    public function addGoal(Goal $goal): void
    {
        if ($this->isStarted() || $this->isFinished()) {
            throw new TaskAlreadyStartedException(
                'Task has already started, you can not add any new goal!'
            );
        }

       $this->goals->add($goal);
    }

    /**
     * @throws TaskAlreadyStartedException
     */
    public function removeGoal(Goal $goal): void
    {
        if ($this->isStarted() || $this->isFinished()) {
            throw new TaskAlreadyStartedException(
                'Task has already started, you can not remove any goal!'
            );
        }

        $this->goals->remove($goal);
    }

    /**
     * @throws TaskAlreadyStartedException
     */
    public function addWorker(User $user): void
    {
        if ($this->isStarted() || $this->isFinished()) {
            throw new TaskAlreadyStartedException(
                sprintf('Task has already %s, you can not add any new worker!', $this->isStarted() ? 'started' : 'finished')
            );
        }

        $this->workers->add($user);

        if (count($this->workers) === $this->requiredWorkers) {
            $this->startDate = new DateTimeImmutable();
        }
    }

    /**
     * @throws TaskAlreadyStartedException
     */
    public function removeWorker(User $user): void
    {
        if ($this->isStarted() || $this->isFinished()) {
            throw new TaskAlreadyStartedException(
                sprintf('Task has already %s, you can not remove any new worker!', $this->isStarted() ? 'started' : 'finished')
            );
        }

        $this->workers->remove($user);
    }

    /**
     * @throws NotPermittedOperation
     */
    public function postpone(User $user, DateTimeImmutable $endDate): void
    {
        if (!$this->creator->getUsername() === $user->getUsername()) {
            throw new NotPermittedOperation(
                'End date can be only postponed by the creator!'
            );
        }

        if ($endDate < $this->endDate) {
            return;
        }

        if ($endDate >= $this->endDate) {
            $this->endDate = $endDate;
        }
    }

    /**
     * @throws TaskAlreadyStartedException
     * @throws GoalDoesNotExistsException
     */
    public function markGoalAsFinished(Goal $goal): void
    {
        if (!$this->isStarted() || $this->isFinished()) {
            throw new TaskAlreadyStartedException(
                'Goal cannot be marked as finished for not started or finished task!'
            );
        }

        $this->goals->markAsFinished($goal);

        if ($this->goals->areAllGoalsFinished()) {
            $this->endDate = new DateTimeImmutable();
        }
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function getRequiredWorkers(): int
    {
        return $this->requiredWorkers;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getGoals(): Goals
    {
        return $this->goals;
    }


    public function getIdentifier(): UuidInterface
    {
        return $this->identifier;
    }
}