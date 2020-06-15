<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Goal\Query\GoalQueryInterface;
use App\Domain\Goal as DomainGoal;
use App\Domain\Repository\GoalRepositoryInterface;
use App\Infrastructure\Entity\Goal;
use App\Infrastructure\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

final class GoalRepository extends ServiceEntityRepository implements GoalRepositoryInterface, GoalQueryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Goal::class);
        $this->entityManager = $entityManager;
    }

    public function add(DomainGoal $domainGoal): void
    {
        $goal = new Goal();
        $goal
            ->setName($domainGoal->getName())
            ->setIsFinished($domainGoal->isFinished())
            ->setGoalIdentifier($domainGoal->getGoalIdentifier()->toString())
            ->setRealizationDescription($domainGoal->getRealizationDescription());

        $this->entityManager->persist($goal);
        $this->entityManager->flush();
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }

    public function findByIdentifier(UuidInterface $uuid): ?Goal
    {
        return $this->findOneBy(['goalIdentifier' => $uuid->toString()]);
    }

    public function markAsDone(DomainGoal $goal): void
    {
        $goal = $this->findByIdentifier($goal->getGoalIdentifier());
        $goal->setIsFinished(true);

        $this->entityManager->persist($goal);
        $this->entityManager->flush();
    }

    public function findTaskByGoal(DomainGoal $goal): ?Task
    {
        $goalEntity = $this->findByIdentifier($goal->getGoalIdentifier());
        if (null === $goalEntity) {
            return null;
        }

        return $goalEntity->getTask();
    }
}