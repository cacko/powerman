<?php

namespace App\Security;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class GoogleAuthenticator extends OAuth2Authenticator implements AuthenticationEntrypointInterface
{


    public function __construct(
        protected ClientRegistry        $clientRegistry,
        protected RouterInterface       $router,
        protected RequestStack          $requestStack,
        protected UserProviderInterface $userProvider
    )
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    protected function getClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient('google_main');
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->getClient();
        $accessToken = $this->fetchAccessToken($client);
        /** @var GoogleUser $googleUser */
        $googleUser = $client->fetchUserFromToken($accessToken);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $client, $googleUser) {

                // Fetch and store the AccessToken
                $session = $this->requestStack->getSession();
                $session->set('access_token', $accessToken);
                $accessToken = $session->get('access_token');

                if ($accessToken->hasExpired()) {
                    $accessToken = $client->refreshAccessToken($accessToken->getRefreshToken());
                    $session->set('access_token', $accessToken);
                }

                $email = $googleUser->getEmail();

                if (!str_ends_with(strtolower($email), "travelfusion.com")) {
                    throw new AuthenticationCredentialsNotFoundException();
                }

                return $this->getUser($accessToken, $this->userProvider);
            }, $googleUser->toArray())
        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $res = $userProvider->loadUserByIdentifier(
            json_encode($this->getClient()->fetchUserFromToken($credentials)->toArray())
        );

        return $res;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $targetUrl = $this->router->generate('app_homepage');

        return new RedirectResponse($targetUrl);

// or, on success, let the request continue to be handled by the controller
//return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(
            '/login/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }
}