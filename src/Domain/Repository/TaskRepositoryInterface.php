<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Task;
use App\Infrastructure\Entity\Task as InfrastructureTask;
use App\Infrastructure\Entity\User;

interface TaskRepositoryInterface
{
    public function add(Task $task): void;

    public function addWorker(InfrastructureTask $task, User $user): void;

    public function removeWorker(InfrastructureTask $task, User $user): void;
}