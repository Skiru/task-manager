<?php

declare(strict_types=1);

namespace App\Application\Task\Query;

use App\Infrastructure\Entity\Task;

interface TaskQueryInterface
{
    /**
     * @return Task[]
     */
    public function findAll(): array;
}