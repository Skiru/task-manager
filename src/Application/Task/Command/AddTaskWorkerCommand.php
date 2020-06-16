<?php

declare(strict_types=1);

namespace App\Application\Task\Command;

use App\Infrastructure\Entity\User;

final class AddTaskWorkerCommand
{
    private int $taskId;
    private User $user;

    public function __construct(int $taskId, User $user)
    {
        $this->taskId = $taskId;
        $this->user = $user;
    }

    public function getTaskId(): int
    {
        return $this->taskId;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}