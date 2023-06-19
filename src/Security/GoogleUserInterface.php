<?php

namespace App\Security;

interface GoogleUserInterface
{

    public function getName(): ?string;

    public function getEmail(): ?string;

    public function getAvatar(): ?string;

}