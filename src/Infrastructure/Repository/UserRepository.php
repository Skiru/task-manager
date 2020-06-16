<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\User\Query\UserQueryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Entity\Task;
use App\Infrastructure\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserRepository extends ServiceEntityRepository implements UserQueryInterface, UserRepositoryInterface
{
    public const ROLE_SUPERUSER = 'ROLE_SUPERUSER';

    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $entityManager;
    }

    public function findById(int $userId): ?User
    {
        return $this->findOneBy(['id' => $userId]);
    }

    public function promote(User $user): void
    {
        $user->setRoles([self::ROLE_SUPERUSER]);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}