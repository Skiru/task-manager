<?php

declare(strict_types=1);

namespace App\Application\Task\Command;

final class CreateTaskCommand
{
    private string $identifier;
    private int $creator_id;
    private int $requiredWorkers;
    private string $startDate;
    private string $endDate;
    private array $goals;

    public function __construct(string $identifier, int $creator_id, int $requiredWorkers, string $startDate, string $endDate, array $goals)
    {
        $this->identifier = $identifier;
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

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}