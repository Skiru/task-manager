<?php

declare(strict_types=1);

namespace App\Controller\ApiController;

use App\Application\Goal\Command\CreateGoalCommand;
use App\Application\Goal\Query\GoalQueryInterface;
use App\Infrastructure\CommandBus\CommandBusInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class GoalController extends AbstractController
{
    private CommandBusInterface $bus;
    private GoalQueryInterface $goalQuery;

    public function __construct(CommandBusInterface $bus, GoalQueryInterface $goalQuery)
    {
        $this->bus = $bus;
        $this->goalQuery = $goalQuery;
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $uuid = Uuid::uuid4();
            $requestBody = json_decode($request->getContent(), true);
            $command = new CreateGoalCommand(
                $uuid,
                $requestBody['name'],
                $requestBody['realizationDescription']
            );

            $this->bus->handle($command);
        } catch (Throwable $exception) {
            return new JsonResponse([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }


        return new JsonResponse([
            'goal' => $uuid->toString()
        ], Response::HTTP_CREATED);
    }

    public function find(string $goalIdentifier): JsonResponse
    {
        $goal = $this->goalQuery->findByIdentifier(Uuid::fromString($goalIdentifier));
        if (null === $goal) {
            return new JsonResponse([
                'error' => 'Goal not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($goal);
    }
}