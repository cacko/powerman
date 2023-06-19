<?php

namespace App\Controller;

use App\Service\WorkstationsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{


    #[Route('/', name: 'app_homepage')]
    public function index(WorkstationsService $workstationsService): Response
    {
        return $this->render('home/index.html.twig', [
            'states' => $workstationsService->getWorkstations($this->getUser())
        ]);
    }


    #[Route('/policy', name: 'app_policy')]
    public function policy(): Response
    {
        return $this->render('home/policy.html.twig', []);
    }
}