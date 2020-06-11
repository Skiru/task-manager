<?php

declare(strict_types=1);

namespace App\Application\Task\Query;

interface TaskQueryInterface
{
    public function findAll(): array;
}