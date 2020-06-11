<?php

declare(strict_types=1);

namespace App\Infrastructure\CommandBus;

final class CommandBus implements CommandBusInterface
{
    private array $handlers = [];

    public function registerHandler(string $commandClass, object $handler): void
    {
        $this->handlers[$commandClass] = $handler;
    }

    public function handle(object $command): void
    {
        $this->handlers[get_class($command)]->handle($command);
    }
}