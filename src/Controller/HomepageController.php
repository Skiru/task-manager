<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends AbstractController
{
    public function homepage(): Response
    {
        return new Response('<h1> Task manager application UKOŃCZONY!</h1>');
    }
}
