<?php

declare(strict_types=1);

namespace App\Controller\FrontendController;

use App\Application\Task\Query\TaskQueryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends AbstractController
{
    private TaskQueryInterface $taskQuery;

    public function __construct(TaskQueryInterface $taskQuery)
    {
        $this->taskQuery = $taskQuery;
    }

    public function show(): Response
    {
        $tasks = $this->taskQuery->findAll();

        return $this->render('frontend/tasks.html.twig', [
            'tasks' => $tasks
        ]);
    }

    public function showUserTasks(): Response
    {
        $tasks = $this->taskQuery->findByCreator($this->getUser());

        return $this->render('frontend/user_tasks.html.twig', [
            'tasks' => $tasks
        ]);
    }
}