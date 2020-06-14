<?php

declare(strict_types=1);

namespace App\Application\Task\Query;

use App\Infrastructure\Entity\Task;
use App\Infrastructure\Entity\User;

interface TaskQueryInterface
{
    /**
     * @return Task[]
     */
    public function findAll(): array;

    /**
     * @return Task[]
     */
    public function findByCreator(User $user): array;

    public function findOneById(int $id): ?Task;
}