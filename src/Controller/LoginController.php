<?php

namespace App\Controller;

use App\Security\GoogleAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    public function __construct(
        protected GoogleAuthenticator $googleAuthenticator
    )
    {
    }

    #[Route('/login', name: 'login_form')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }
        return $this->render('login/index.html.twig', []);
    }

    #[Route('/login/google', name: 'connect_google')]
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('google_main')
            ->redirect();
    }

    #[Route('/login/google/check', name: 'connect_google_check')]
    public function connectCheckAction(Request $request): RedirectResponse|JsonResponse
    {
        if (!$this->getUser()) {
            $this->addFlash('error', "User not fo");
            return $this->redirectToRoute('login_form');
        } else {
            $this->addFlash('success', "logged");
            return $this->redirectToRoute('default');
        }

    }

    /**
     * @throws \Exception
     */
    #[Route('/logout', name: 'app_logout')]
    public function logout(): never
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}