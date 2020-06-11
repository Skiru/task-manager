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
}