<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Goal\Query\GoalQueryInterface;
use App\Application\Task\Query\TaskQueryInterface;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\Task as DomainTask;
use App\Infrastructure\Entity\Task;
use App\Infrastructure\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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
            ->setUuid($domainTask->getIdentifier()->toString())
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
        return $this->findBy([], ['startDate' => 'ASC']);
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

    public function findTasks(User $user): array
    {
        $workersTasks = $this->createQueryBuilder('task')
            ->innerJoin(User::class, 'u', 'WITH', ':user_id MEMBER OF task.workers')
            ->setParameter('user_id', $user->getId())
            ->getQuery()
            ->getResult();

        $creatorsTasks = $this->findBy(['creator' => $user->getId()]);

        $tasks = array_unique(array_merge($workersTasks, $creatorsTasks));
        usort($tasks, function (Task $task1, Task $task2) {
            return $task1->getStartDate() > $task2->getStartDate();
        });

        return $tasks;
    }

    public function updateEndDate(DomainTask $task): void
    {
        $entityTask = $this->findByIdentifier($task->getIdentifier());
        $entityTask->setEndDate(new DateTime($task->getEndDate()->format('Y-m-d H:i:s')));

        $this->entityManager->persist($entityTask);
        $this->entityManager->flush();
    }
}
