<?php

declare(strict_types=1);

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class GoogleController extends AbstractController
{
    public function connect(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect(['profile', 'email'], []);
    }

    public function connectCheck(Request $request, ClientRegistry $clientRegistry): RedirectResponse
    {
        return $this->redirectToRoute('homepage');
    }
}