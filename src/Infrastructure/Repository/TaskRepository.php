<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Goal\Query\GoalQueryInterface;
use App\Application\Task\Query\TaskQueryInterface;
use App\Domain\Goal;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\Task as DomainTask;
use App\Infrastructure\Entity\Task;
use App\Infrastructure\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

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
            ->setStartDate(new DateTime($domainTask->getStartDate()->format('Y-m-d H:i:s')))
            ->setEndDate(new DateTime($domainTask->getEndDate()->format('Y-m-d H:i:s')))
            ->setRequiredWorkers($domainTask->getRequiredWorkers())
            ->setGoals($goals);

        $user = $domainTask->getCreator();
        $user->addCreatedTask($task);

        $this->entityManager->persist($task);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }

    public function findByCreator(User $user): array
    {
        return $this->findBy(['creator' => $user]);
    }

    public function findOneById(int $id): ?Task
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findByIdentifier(UuidInterface $uuid): ?Task
    {
        return $this->findOneBy(['uuid' => $uuid->toString()]);
    }

    public function addWorker(DomainTask $task, User $user): void
    {
        $entityTask = $this->findByIdentifier($task->getIdentifier());
        $entityTask
            ->addWorker($user)
            ->setStartDate(new DateTime($task->getStartDate()->format('Y-m-d H:i:s')));

        $this->entityManager->persist($entityTask);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function removeWorker(DomainTask $task, User $user): void
    {
        $taskEntity = $this->findByIdentifier($task->getIdentifier());
        $taskEntity->removeWorker($user);

        $this->entityManager->persist($taskEntity);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}