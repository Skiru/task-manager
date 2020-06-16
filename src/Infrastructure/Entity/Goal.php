<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\UserRepository")
 */
class Goal implements \JsonSerializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=36)
     */
    private string $goalIdentifier;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isFinished;

    /**
     * @ORM\Column(type="text")
     */
    private string $realizationDescription;

    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="goals")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     */
    private Task $task;

    public function setGoalIdentifier(string $goalIdentifier): Goal
    {
        $this->goalIdentifier = $goalIdentifier;

        return $this;
    }

    public function setName(string $name): Goal
    {
        $this->name = $name;

        return $this;
    }

    public function setIsFinished(bool $isFinished): Goal
    {
        $this->isFinished = $isFinished;

        return $this;
    }

    public function setRealizationDescription(string $realizationDescription): Goal
    {
        $this->realizationDescription = $realizationDescription;

        return $this;
    }

    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'goal_identifier' => $this->goalIdentifier,
            'name' => $this->name,
            'is_finished' => $this->isFinished,
            'description' => $this->realizationDescription
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getGoalIdentifier(): string
    {
        return $this->goalIdentifier;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    public function getRealizationDescription(): string
    {
        return $this->realizationDescription;
    }

    public function getTask(): Task
    {
        return $this->task;
    }
}