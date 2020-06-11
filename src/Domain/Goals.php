<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exception\GoalDoesNotExistsException;

final class Goals
{
    /**
     * @var Goal[]
     */
    private array $goals;

    public function __construct(array $goals)
    {
        $this->goals = $goals;
    }

    public function add(Goal $goal): void
    {
        $this->goals[$goal->getGoalIdentifier()->toString()] = $goal;
    }

    public function remove(Goal $goal): void
    {
        if (!isset($this->goals[$goal->getGoalIdentifier()->toString()])) {
            return;
        }

        unset($this->goals[$goal->getGoalIdentifier()->toString()]);
    }

    public function areAllGoalsFinished(): bool
    {
        foreach ($this->goals as $goal) {
            if (!$goal->isFinished()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @throws GoalDoesNotExistsException
     */
    public function markAsFinished(Goal $goal): void
    {
        if (!isset($this->goals[$goal->getGoalIdentifier()->toString()])) {
            throw new GoalDoesNotExistsException(
                'Goal you would like to mark as finished does not exist or does not belong to that task!'
            );
        }

        $this->goals[$goal->getGoalIdentifier()->toString()]->markAsFinished();
    }
}