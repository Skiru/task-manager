<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\TaskRepository")
 */
final class Task
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\OneToOne(targetEntity="User")
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
     * @ORM\OneToMany(targetEntity="User", mappedBy="task")
     */
    private $workers;

    public function __construct()
    {
        $this->goals = [];
        $this->workers = [];
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

    public function setGoals(array $goals): Task
    {
        $this->goals = $goals;
        return $this;
    }

    public function setWorkers(array $workers): Task
    {
        $this->workers = $workers;
        return $this;
    }
}