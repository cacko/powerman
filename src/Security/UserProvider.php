<?php

namespace App\Security;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\User\OAuthUser;
use KnpU\OAuth2ClientBundle\Security\User\OAuthUserProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider extends OAuthUserProvider
{

    public function __construct(
        protected RequestStack   $requestStack,
        protected ClientRegistry $clientRegistry

    )
    {
        $roles = ['ROLE_USER', 'ROLE_OAUTH_USER'];
        parent::__construct($roles);
    }

    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me.
     *
     * If you're not using these features, you do not need to implement
     * this method.
     *
     * @throws UserNotFoundException if the user is not found
     */
    public function loadUserByIdentifier($identifier): UserInterface
    {
        $googleUser = json_decode($identifier, true);
        return new User(
            $googleUser['sub'] ?: "",
            ['ROLE_USER', 'ROLE_OAUTH_USER'],
            $googleUser['email'] ?: "",
            $googleUser['name'] ?: "Unknown",
            $googleUser['picture'] ?: "",
        );
    }

    public function supportsClass($class): bool
    {
        return User::class === $class;
    }

    protected function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    protected function getClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient("google_main");
    }

    public function refreshUser(UserInterface $user): UserInterface
    {

        if (!$user instanceof OAuthUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }
        try {
            $token = $this->getSession()->get("access_token");
            $googleUser = $this->getClient()->fetchUserFromToken($token);
            return $this->loadUserByUsername(json_encode($googleUser->toArray()));
        } catch (IdentityProviderException $e) {
            throw new UserNotFoundException();
        }

    }
}
