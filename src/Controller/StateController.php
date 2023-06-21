<?php

namespace App\Controller;

use App\Service\WorkstationsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/state/{id}', name: 'state_update', methods: 'post')]
    public function update(): Response
    {
        throw new NotImplementedException();
    }

}