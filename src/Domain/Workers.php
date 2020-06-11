<?php

declare(strict_types=1);

namespace App\Domain;

use App\Infrastructure\Entity\User;
use Countable;

final class Workers implements Countable
{
    /**
     * @var User[]
     */
    private array $workers;

    public function __construct(array $workers)
    {
        $this->workers = $workers;
    }

    public function add(User $user): void
    {
        $this->workers[$user->getUsername()] = $user;
    }

    public function remove(User $user): void
    {
        if (!isset($this->workers[$user->getUsername()])) {
            return;
        }

        unset($this->workers[$user->getUsername()]);
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->workers);
    }
}
