<?php

declare(strict_types=1);

namespace App\Application\Task\Command;

final class CreateTaskCommand
{
    private int $creator_id;
    private int $requiredWorkers;
    private string $startDate;
    private string $endDate;
    private array $goals;

    public function __construct(int $creator_id, int $requiredWorkers, string $startDate, string $endDate, array $goals)
    {
        $this->creator_id = $creator_id;
        $this->requiredWorkers = $requiredWorkers;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->goals = $goals;
    }

    public function getCreatorId(): int
    {
        return $this->creator_id;
    }

    public function getRequiredWorkers(): int
    {
        return $this->requiredWorkers;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function getGoals(): array
    {
        return $this->goals;
    }
}