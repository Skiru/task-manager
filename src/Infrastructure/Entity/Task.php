<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\TaskRepository")
 */
class Task
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
    private string $uuid;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="createdTasks")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    private User $creator;

    /**
     * @ORM\Column(type="integer")
     */
    private int $requiredWorkers;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $endDate;

    /**
     * @ORM\OneToMany(targetEntity="Goal", mappedBy="task")
     */
    private $goals;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="tasks")
     * @ORM\JoinTable(name="task_user")
     */
    private $workers;

    public function __construct()
    {
        $this->goals = new ArrayCollection();
        $this->workers = new ArrayCollection();
    }

    public function setCreator($creator): Task
    {
        $this->creator = $creator;
        return $this;
    }

    public function setRequiredWorkers(int $requiredWorkers): Task
    {
        $this->requiredWorkers = $requiredWorkers;
        return $this;
    }

    public function setStartDate(DateTime $startDate): Task
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function setEndDate(DateTime $endDate): Task
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function setWorkers(array $workers): Task
    {
        $this->workers = $workers;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function getRequiredWorkers(): int
    {
        return $this->requiredWorkers;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    /**
     * @return Collection|User[]
     */
    public function getWorkers(): Collection
    {
        return $this->workers;
    }

    /**
     * @return Collection|Goal[]
     */
    public function getGoals(): Collection
    {
        return $this->goals;
    }

    public function addGoal(Goal $goal): Task
    {
        $this->goals[] = $goal;
        $goal->setTask($this);

        return $this;
    }

    /**
     * @param Goal[] $goals
     */
    public function setGoals(array $goals): Task
    {
        foreach ($goals as $goal) {
            $this->addGoal($goal);
        }

        return $this;
    }

    public function addWorker(User $user): Task
    {
        $this->workers[] = $user;
        $user->addTask($this);

        return $this;
    }

    public function removeWorker(User $user): Task
    {
        $this->workers->removeElement($user);
        $user->removeTask($this);

        return $this;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): Task
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function isStarted(): bool
    {
        return $this->getStartDate() < new DateTime();
    }

    public function isFinished(): bool
    {
        return $this->getEndDate() <= new DateTime();
    }
}