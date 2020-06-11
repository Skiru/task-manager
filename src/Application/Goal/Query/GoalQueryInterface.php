<?php

declare(strict_types=1);

namespace App\Application\Goal\Query;

interface GoalQueryInterface
{
    public function findAll(): array;
}