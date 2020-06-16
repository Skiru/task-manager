<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\User\Query\UserQueryInterface;
use App\Domain\Exception\UserDoesNotExistsException;
use App\Domain\Repository\UserRepositoryInterface;

final class PromoteUserCommandHandler
{
    private UserQueryInterface $userQuery;
    private UserRepositoryInterface $userRepository;

    public function __construct(UserQueryInterface $userQuery, UserRepositoryInterface $userRepository)
    {
        $this->userQuery = $userQuery;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws UserDoesNotExistsException
     */
    public function handle(PromoteUserCommand $command): void
    {
        $user = $this->userQuery->findById($command->getId());
        if (null === $user) {
            throw new UserDoesNotExistsException(sprintf('User with id %d does not exist!', $command->getId()));
        }

       $this->userRepository->promote($user);
    }
}