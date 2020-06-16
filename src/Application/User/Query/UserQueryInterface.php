<?php

declare(strict_types=1);

namespace App\Application\User\Query;

use App\Infrastructure\Entity\User;

interface UserQueryInterface
{
    public function findById(int $userId): ?User;
}