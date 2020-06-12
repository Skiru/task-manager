<?php

declare(strict_types=1);

namespace App\Controller\ApiController;

use App\Application\Task\Command\CreateTaskCommand;
use App\Domain\Exception\AbstractDomainException;
use App\Infrastructure\CommandBus\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
            $content = json_decode($request->getContent(), true);
            $command = new CreateTaskCommand(
                (int)$content['creator_id'],
                (int)$content['required_workers'],
                $content['start_date'],
                $content['end_date'],
                $content['goal']
            );

            $this->bus->handle($command);
        } catch (AbstractDomainException $exception) {
            return new JsonResponse([
                'error' => 'task create failed' . $exception->getMessage()
            ]);
        }


        return new JsonResponse([
           'task' => 'task created'
        ]);
    }
}