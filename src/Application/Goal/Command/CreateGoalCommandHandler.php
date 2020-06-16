<?php

declare(strict_types=1);

namespace App\Application\Goal\Command;

use App\Domain\Goal;
use App\Domain\Repository\GoalRepositoryInterface;

final class CreateGoalCommandHandler
{
    private GoalRepositoryInterface $goalRepository;

    public function __construct(GoalRepositoryInterface $goalRepository)
    {
        $this->goalRepository = $goalRepository;
    }

    public function handle(CreateGoalCommand $command): void
    {
        $goal = new Goal(
            $command->getUuid(),
            $command->getName(),
            false,
            $command->getRealizationDescription()
        );

        $this->goalRepository->add($goal);
    }
}