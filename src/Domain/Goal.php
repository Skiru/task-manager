<?php

declare(strict_types=1);

namespace App\Domain;

use Ramsey\Uuid\Uuid;

final class Goal
{
    private Uuid $goalIdentifier;
    private string $name;
    private bool $isFinished;
    private string $realizationDescription;

    public function __construct(
        Uuid $goalIdentifier,
        string $name,
        bool $isFinished,
        string $realizationDescription
    ) {
        $this->goalIdentifier = $goalIdentifier;
        $this->name = $name;
        $this->isFinished = $isFinished;
        $this->realizationDescription = $realizationDescription;
    }

    public function getGoalIdentifier(): Uuid
    {
        return $this->goalIdentifier;
    }

    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    public function markAsFinished(): void
    {
        if ($this->isFinished) {
            return;
        }

        $this->isFinished = true;
    }
}