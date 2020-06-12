<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Goal\Query\GoalQueryInterface;
use App\Application\Task\Query\TaskQueryInterface;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\Task as DomainTask;
use App\Infrastructure\Entity\Task;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Persistence\ManagerRegistry;

final class TaskRepository extends ServiceEntityRepository implements TaskRepositoryInterface, TaskQueryInterface
{
    private EntityManagerInterface $entityManager;

    private GoalQueryInterface $goalQuery;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, GoalQueryInterface $goalQuery)
    {
        parent::__construct($registry, Task::class);
        $this->entityManager = $entityManager;
        $this->goalQuery = $goalQuery;
    }

    public function add(DomainTask $domainTask): void
    {
        $goals = [];
        foreach ($domainTask->getGoals()->getGoals() as $goal) {
            $goals[] = $this->goalQuery->findByIdentifier($goal->getGoalIdentifier());
        }

        $task = new Task();
        $task
            ->setCreator($domainTask->getCreator())
            ->setStartDate(new DateTime($domainTask->getStartDate()->format('Y-m-d H:i:s')))
            ->setEndDate(new DateTime($domainTask->getEndDate()->format('Y-m-d H:i:s')))
            ->setRequiredWorkers($domainTask->getRequiredWorkers())
            ->setGoals($goals);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }
}