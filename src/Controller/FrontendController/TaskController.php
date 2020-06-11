<?php

declare(strict_types=1);

namespace App\Controller\FrontendController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    public function show()
    {
        return $this->render('frontend/tasks.html.twig', []);
    }
}