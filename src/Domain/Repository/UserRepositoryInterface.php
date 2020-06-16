<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Infrastructure\Entity\User;

interface UserRepositoryInterface
{
    public function promote(User $user): void;
}