<?php

declare(strict_types=1);

namespace App\Controller\FrontendController;

use App\Application\Task\Query\TaskQueryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    private TaskQueryInterface $taskQuery;

    public function __construct(TaskQueryInterface $taskQuery)
    {
        $this->taskQuery = $taskQuery;
    }

    public function show()
    {
        $tasks = $this->taskQuery->findAll();

        dump($tasks);die;

        return $this->render('frontend/tasks.html.twig', [
            'tasks' => $tasks
        ]);
    }
}