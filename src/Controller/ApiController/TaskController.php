<?php

declare(strict_types=1);

namespace App\Controller\ApiController;

use App\Application\Task\Command\AddTaskWorkerCommand;
use App\Application\Task\Command\CreateTaskCommand;
use App\Application\Task\Command\RemoveTaskWorkerCommand;
use App\Application\Task\Query\TaskQueryInterface;
use App\Infrastructure\CommandBus\CommandBusInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

final class TaskController extends AbstractController
{
    private CommandBusInterface $bus;

    public function __construct(CommandBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $uuid = Uuid::uuid4()->toString();

            $content = json_decode($request->getContent(), true);
            $command = new CreateTaskCommand(
                $uuid,
                (int)$content['creator_id'],
                (int)$content['required_workers'],
                $content['start_date'],
                $content['end_date'],
                $content['goal']
            );

            $this->bus->handle($command);
        } catch (Throwable $exception) {
            return new JsonResponse([
                'error' => 'Task create failed because of: ' . $exception->getMessage()
            ], 400);
        }


        return new JsonResponse([
            'task' => 'task created'
        ]);
    }

    public function addWorker(int $taskId): JsonResponse
    {
        try {
            $command = new AddTaskWorkerCommand($taskId, $this->getUser());
            $this->bus->handle($command);
        } catch (Throwable $exception) {
            return new JsonResponse(['error' => sprintf('Could not participate in task because of: %s', $exception->getMessage())], 400);
        }

        return new JsonResponse([
           'task' => 'Successfully participated in task'
        ]);
    }

    public function removeWorker(int $taskId): JsonResponse
    {
        try {
            $command = new RemoveTaskWorkerCommand($taskId, $this->getUser());
            $this->bus->handle($command);
        } catch (Throwable $exception) {
            return new JsonResponse(['error' => sprintf('Could not participate in task because of: %s', $exception->getMessage())], 400);
        }

        return new JsonResponse([
            'task' => 'Successfully resigned from a task'
        ]);
    }
}