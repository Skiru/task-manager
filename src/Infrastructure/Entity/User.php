<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $givenName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $familyName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $email;

    /**
     * "sub" field in request
     * @ORM\Column(type="string", length=255)
     */
    private string $googleId;

    /**
     * @ORM\Column(type="json", length=255)
     */
    private array $roles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $picture;

    /**
     * @ORM\ManyToMany(targetEntity="Task", mappedBy="workers")
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="creator")
     */
    private $createdTasks;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->tasks = new ArrayCollection();
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getPassword(): string
    {
        //Only log in with google
        return '';
    }

    public function getSalt()
    {
        //No pass required
        return null;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        return null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }

    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    public function setGivenName(string $givenName): User
    {
        $this->givenName = $givenName;
        return $this;
    }

    public function setFamilyName(string $familyName): User
    {
        $this->familyName = $familyName;
        return $this;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function setGoogleId(string $googleId): User
    {
        $this->googleId = $googleId;
        return $this;
    }

    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    public function setPicture(string $picture): User
    {
        $this->picture = $picture;
        return $this;
    }

    public static function fromParameters(
        string $name,
        string $givenName,
        string $familyName,
        string $email,
        string $googleId,
        string $picture
    ): User {
        $user = new User();

        return $user
            ->setName($name)
            ->setGivenName($givenName)
            ->setFamilyName($familyName)
            ->setEmail($email)
            ->setGoogleId($googleId)
            ->setPicture($picture);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGivenName(): string
    {
        return $this->givenName;
    }

    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getGoogleId(): string
    {
        return $this->googleId;
    }

    public function addCreatedTask(Task $task): User
    {
        $this->createdTasks[] = $task;
        $task->setCreator($this);

        return $this;
    }

    public function addTask(Task $task): User
    {
        $this->tasks[] = $task;

        return $this;
    }

    public function removeTask(Task $task): User
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
        }

        return $this;
    }
}