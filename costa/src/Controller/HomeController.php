<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }


    #[Route('/policy', name: 'app_policy')]
    public function policy(): Response
    {
        return $this->render('component/policy.html.twig', []);
    }
}