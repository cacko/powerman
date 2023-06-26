<?php

namespace App\Controller;

use App\Service\WorkstationsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Polyfill\Intl\Icu\Exception\NotImplementedException;

class StateController extends AbstractController
{

    public function __construct(
        protected WorkstationsService $workstationsService
    )
    {

    }

    #[Route('/state', name: 'state_get')]
    public function view(Request $request): Response
    {
        $service = $this->workstationsService;
        $entity = $service->getWorkstation($request->getHost() ?: 'dev031');
        return new Response($entity->state->trigger);
    }

    #[Route('/state/resume/{id}', name: 'state_resume', methods: 'post')]
    #[IsGranted('update', 'state', 'KMY', 404)]
    public function resume(): Response
    {
        throw new NotImplementedException();
    }

    #[Route('/state/suspend/{id}', name: 'state_suspend', methods: 'post')]
    #[IsGranted('update', 'state', 'KMY', 404)]
    public function suspend(): Response
    {
        throw new NotImplementedException();
    }

}