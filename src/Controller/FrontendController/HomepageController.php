<?php

declare(strict_types=1);

namespace App\Controller\FrontendController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends AbstractController
{
    public function homepage(): Response
    {
        return $this->render('frontend/homepage.html.twig', []);
    }
}
