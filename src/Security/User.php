<?php

namespace App\Security;

use KnpU\OAuth2ClientBundle\Security\User\OAuthUser;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\Security\Core\User\UserInterface;

class User extends OAuthUser implements GoogleUserInterface
{

    public function __construct(
        $username,
        array $roles,
        protected string $email,
        protected string $name,
        protected string $avatar
    )
    {
        parent::__construct($username, $roles);
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
