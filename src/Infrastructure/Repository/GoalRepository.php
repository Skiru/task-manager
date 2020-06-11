<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Infrastructure\Entity\Goal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class GoalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Goal::class);
    }
}