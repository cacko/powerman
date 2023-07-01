<?php

namespace App\Form;

use App\Entity\WorkstationEntity;
use App\Enum\StateAction;
use App\Service\WorkstationsService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class WorkstationVoter extends Voter
{

    public function __construct(
        protected WorkstationsService $workstationsService,
        protected Security $security
    ) {

    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!StateAction::tryFrom($attribute)) {
            return true;
        }
        return $this->security->isGranted('ROLE_OAUTH_USER');
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $this->security->getUser();
        $userWorkstations = $this->workstationsService->getWorkstations($user);
        return in_array($subject, array_map(fn(WorkstationEntity $ws) => $ws->hostname, $userWorkstations));
    }
}