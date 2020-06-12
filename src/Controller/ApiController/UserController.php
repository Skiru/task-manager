<?php

declare(strict_types=1);

namespace App\Controller\ApiController;

use App\Application\User\Command\PromoteUserCommand;
use App\Domain\Exception\UserDoesNotExistsException;
use App\Infrastructure\CommandBus\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class UserController extends AbstractController
{
    private CommandBusInterface $bus;

    public function __construct(CommandBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function promote(int $userId): RedirectResponse
    {
        try {
          $command = new PromoteUserCommand($userId);

          $this->bus->handle($command);
        } catch (UserDoesNotExistsException $exception) {
            $this->addFlash('danger', $exception->getMessage());

            return $this->redirectToRoute('app_tasks');
        }

        $this->addFlash('success', 'You have been successfully promoted!');

        return $this->redirectToRoute('app_tasks');
    }
}