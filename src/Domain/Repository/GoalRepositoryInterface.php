<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Goal;
use App\Infrastructure\Entity\Task;

interface GoalRepositoryInterface
{
    public function add(Goal $goal): void;

    public function markAsDone(Goal $goal): void;

    public function findTaskByGoal(Goal $goal): ?Task;
}