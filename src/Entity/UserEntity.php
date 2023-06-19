<?php

namespace App\Entity;

class UserEntity extends AbstractAppEntity
{
    public function __construct(
        protected string $username,
        protected string $email,
        protected array $workstations
    ) {

    }
}