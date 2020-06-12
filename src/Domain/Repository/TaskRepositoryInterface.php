<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Task;

interface TaskRepositoryInterface
{
    public function add(Task $task): void;
}