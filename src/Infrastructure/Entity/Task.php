<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity;

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
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    private int $creator;

    /**
     * @ORM\Column(type="integer")
     */
    private int $requiredWorkers;

    /**
     * @ORM\Column(type="datetime")
     */
    private string $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private string $endDate;

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
}