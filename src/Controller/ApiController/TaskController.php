<?php

declare(strict_types=1);

namespace App\Controller\ApiController;

use App\Application\Task\Query\TaskQueryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    public function create(Request $request): JsonResponse
    {
        return new JsonResponse([
           'task' => 'url_to_task_resource'
        ]);
    }
}