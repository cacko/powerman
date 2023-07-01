<?php

namespace App\Controller;

use App\Entity\StateEntity;
use App\Entity\WorkstationEntity;
use App\Enum\StateAction;
use App\Enum\StateWhen;
use App\Form\State;
use App\Form\Type\StateType;
use App\Service\WorkstationsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class StateController extends AbstractController
{

    public function __construct(
        protected WorkstationsService $workstationsService,
        protected Security            $security
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

    #[Route('/state/resume/{workstation}', name: 'state_resume', methods: 'post')]
    #[IsGranted('resume', 'workstation', 'KMY', 404)]
    public function resume(string $workstation, Request $request): Response
    {
        $state = $this->getState($request,StateAction::RESUME);
        $workstation = $this->workstationsService->getWorkstation($workstation);
        if ($state->getWhen() == StateWhen::NOW) {
            $wsState = $workstation->state;
            $workstation->writeState((array) new StateEntity(
                0, 0, $wsState->last_ack
            ));
            return $this->redirectToRoute('app_homepage');

        }
    }

    #[Route('/state/suspend/{workstation}', name: 'state_suspend', methods: 'post')]
    #[IsGranted('suspend', 'workstation', 'KMY', 404)]
    public function suspend(string $workstation, Request $request): Response
    {
        $state = $this->getState($request,StateAction::SUSPEND);
        $workstation = $this->workstationsService->getWorkstation($workstation);
        return $this->redirectToRoute('app_homepage');
    }

    protected function getState(Request $request, StateAction $action): State
    {
        $state = new State();
        $form = $this->createForm(StateType::class, $state);
        $form->handleRequest($request);
        return $form->getData();
    }

}