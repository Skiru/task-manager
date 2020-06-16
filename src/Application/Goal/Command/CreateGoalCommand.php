<?php

declare(strict_types=1);

namespace App\Application\Goal\Command;

use Ramsey\Uuid\Uuid;

final class CreateGoalCommand
{
    private Uuid $uuid;
    private string $name;
    private string $realizationDescription;

    public function __construct(Uuid $uuid, string $name, string $realizationDescription)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->realizationDescription = $realizationDescription;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRealizationDescription(): string
    {
        return $this->realizationDescription;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }
}