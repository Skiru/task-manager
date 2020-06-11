<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Goal;

interface GoalRepositoryInterface
{
    public function add(Goal $goal): void;
}