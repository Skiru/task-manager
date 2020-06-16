<?php

declare(strict_types=1);

namespace App\Application\Goal\Query;

use App\Infrastructure\Entity\Goal;
use Ramsey\Uuid\UuidInterface;

interface GoalQueryInterface
{
    /**
     * @return Goal[]
     */
    public function findAll(): array;
    public function findByIdentifier(UuidInterface $uuid): ?Goal;
}