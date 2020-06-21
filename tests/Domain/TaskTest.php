<?php

declare(strict_types=1);

namespace App\Tests\Domain;

use App\Domain\Goals;
use App\Domain\Task;
use App\Domain\Workers;
use App\Infrastructure\Entity\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class TaskTest extends TestCase
{
    public function testInstantiate()
    {
        //Arrange
        $uuid = Uuid::uuid4();
        $user = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $requiredWorkers = 5;
        //Good idea is to replace direct DateRime with calendar
        $startDate = $this->getMockBuilder(\DateTimeImmutable::class)->disableOriginalConstructor()->getMock();
        $endDate = $this->getMockBuilder(\DateTimeImmutable::class)->disableOriginalConstructor()->getMock();
        $goals = new Goals([]);
        $workers = new Workers([]);

        //Act
        $task = new Task(
            $uuid,
            $user,
            $requiredWorkers,
            $startDate,
            $endDate,
            $goals,
            $workers
        );

        //Assert
        $this->assertInstanceOf(Task::class, $task);
    }
}