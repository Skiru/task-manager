<?php

declare(strict_types=1);

namespace App\Domain;

use Ramsey\Uuid\UuidInterface;

final class Goal
{
    private UuidInterface $goalIdentifier;
    private string $name;
    private bool $isFinished;
    private string $realizationDescription;

    public function __construct(
        UuidInterface $goalIdentifier,
        string $name,
        bool $isFinished,
        string $realizationDescription
    ) {
        $this->goalIdentifier = $goalIdentifier;
        $this->name = $name;
        $this->isFinished = $isFinished;
        $this->realizationDescription = $realizationDescription;
    }

    public function getGoalIdentifier(): UuidInterface
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getRealizationDescription(): string
    {
        return $this->realizationDescription;
    }
}