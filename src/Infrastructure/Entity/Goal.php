<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\UserRepository")
 */
final class Goal
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
    private $task;

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
}